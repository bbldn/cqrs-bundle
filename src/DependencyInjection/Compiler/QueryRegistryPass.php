<?php

namespace BBLDN\CQRS\DependencyInjection\Compiler;

use ReflectionException;
use BBLDN\CQRS\Helper\Context;
use BBLDN\CQRS\QueryBus\QueryRegistry;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BBLDN\CQRS\QueryBus\Annotation\QueryHandler as QueryHandlerAnnotation;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface as CompilerPass;

class QueryRegistryPass implements CompilerPass
{
    private Context $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     * @throws ReflectionException
     */
    public function process(ContainerBuilder $container): void
    {
        $annotationReader = new AnnotationReader();

        $queryClassMap = [];
        $serviceMap = $container->findTaggedServiceIds($this->context->getCommandTag());
        foreach ($serviceMap as $serviceId => $_) {
            $queryClassName = (string)$container->getDefinition($serviceId)->getClass();
            $queryReflectionClass = $container->getReflectionClass($queryClassName);
            if (null === $queryReflectionClass) {
                continue;
            }

            $annotation = $annotationReader->getClassAnnotation($queryReflectionClass, QueryHandlerAnnotation::class);
            if (null === $annotation) {
                continue;
            }

            $queryHandlerClass = $annotation->class;
            if (true === $container->hasDefinition($queryHandlerClass)) {
                $container->getDefinition($queryHandlerClass)->setPublic(true);
                $queryClassMap[$queryClassName] = $queryHandlerClass;
            }
        }

        $definition = new Definition();
        $definition->setClass(QueryRegistry::class);
        $definition->setArgument(0, $queryClassMap);

        $container->setDefinition(QueryRegistry::class, $definition);
    }
}
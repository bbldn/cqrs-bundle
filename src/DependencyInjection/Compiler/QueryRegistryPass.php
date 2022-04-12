<?php

namespace BBLDN\CQRS\DependencyInjection\Compiler;

use ReflectionException;
use BBLDN\CQRS\Helper\Context;
use BBLDN\CQRS\QueryBus\QueryRegistry;
use BBLDN\CQRS\Helper\AnnotationReader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BBLDN\CQRS\QueryBus\Annotation\QueryHandler as Annotation;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface as CompilerPass;

class QueryRegistryPass implements CompilerPass
{
    private Context $context;

    private AnnotationReader $annotationReader;

    /**
     * @param Context $context
     * @param AnnotationReader $annotationReader
     */
    public function __construct(
        Context $context,
        AnnotationReader $annotationReader
    )
    {
        $this->context = $context;
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     * @throws ReflectionException
     */
    public function process(ContainerBuilder $container): void
    {
        $queryClassMap = [];
        $serviceMap = $container->findTaggedServiceIds($this->context->getCommandTag());
        foreach ($serviceMap as $serviceId => $_) {
            $queryClassName = (string)$container->getDefinition($serviceId)->getClass();
            $queryReflectionClass = $container->getReflectionClass($queryClassName);
            if (null === $queryReflectionClass) {
                continue;
            }

            /** @var Annotation|null $annotation */
            $annotation = $this->annotationReader->getClassAnnotation($queryReflectionClass, Annotation::class);
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
        $definition->setLazy(true);
        $definition->setClass(QueryRegistry::class);
        $definition->setArgument(0, $queryClassMap);

        $container->setDefinition(QueryRegistry::class, $definition);
        $container->setAlias(QueryRegistry::class, $this->context->getQueryRegistryTag());
    }
}
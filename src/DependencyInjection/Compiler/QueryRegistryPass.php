<?php

namespace BBLDN\CQRSBundle\DependencyInjection\Compiler;

use ReflectionException;
use BBLDN\CQRSBundle\QueryBus\QueryRegistry;
use BBLDN\CQRSBundle\Helper\AnnotationReader;
use BBLDN\CQRSBundle\DependencyInjection\Helper\Context;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BBLDN\CQRSBundle\QueryBus\Annotation\QueryHandler as Annotation;
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
        $serviceMap = $container->findTaggedServiceIds($this->context->getQueryTag());
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

        $definition = $container->getDefinition(QueryRegistry::class);
        $definition->setArgument(0, $queryClassMap);
    }
}

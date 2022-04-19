<?php

namespace BBLDN\CQRSBundle\DependencyInjection\Compiler;

use ReflectionException;
use BBLDN\CQRSBundle\Helper\AnnotationReader;
use BBLDN\CQRSBundle\CommandBus\CommandRegistry;
use BBLDN\CQRSBundle\DependencyInjection\Helper\Context;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BBLDN\CQRSBundle\CommandBus\Annotation\CommandHandler as Annotation;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface as CompilerPass;

class CommandRegistryPass implements CompilerPass
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
        $commandClassMap = [];
        $serviceMap = $container->findTaggedServiceIds($this->context->getCommandTag());
        foreach ($serviceMap as $serviceId => $_) {
            $commandClassName = (string)$container->getDefinition($serviceId)->getClass();
            $commandReflectionClass = $container->getReflectionClass($commandClassName);
            if (null === $commandReflectionClass) {
                continue;
            }

            /** @var Annotation|null $annotation */
            $annotation = $this->annotationReader->getClassAnnotation($commandReflectionClass, Annotation::class);
            if (null === $annotation) {
                continue;
            }

            $commandHandlerClass = $annotation->class;
            if (true === $container->hasDefinition($commandHandlerClass)) {
                $container->getDefinition($commandHandlerClass)->setPublic(true);
                $commandClassMap[$commandClassName] = $commandHandlerClass;
            }
        }

        $definition = $container->getDefinition(CommandRegistry::class);
        $definition->setArgument(0, $commandClassMap);
    }
}
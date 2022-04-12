<?php

namespace BBLDN\CQRS\DependencyInjection\Compiler;

use ReflectionException;
use BBLDN\CQRS\Helper\Context;
use BBLDN\CQRS\Helper\AnnotationReader;
use BBLDN\CQRS\CommandBus\CommandRegistry;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BBLDN\CQRS\CommandBus\Annotation\CommandHandler as Annotation;
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

        $definition = new Definition();
        $definition->setLazy(true);
        $definition->setClass(CommandRegistry::class);
        $definition->setArgument(0, $commandClassMap);

        $container->setDefinition(CommandRegistry::class, $definition);
        $container->setAlias(CommandRegistry::class, $this->context->getCommandRegistryTag());
    }
}
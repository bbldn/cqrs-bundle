<?php

namespace BBLDN\CQRS\DependencyInjection\Compiler;

use ReflectionException;
use BBLDN\CQRS\Helper\Context;
use BBLDN\CQRS\CommandBus\CommandRegistry;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BBLDN\CQRS\CommandBus\Annotation\CommandHandler as CommandHandlerAnnotation;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface as CompilerPass;

class CommandRegistryPass implements CompilerPass
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

        $commandClassMap = [];
        $serviceMap = $container->findTaggedServiceIds($this->context->getCommandTag());
        foreach ($serviceMap as $serviceId => $_) {
            $commandClassName = (string)$container->getDefinition($serviceId)->getClass();
            $commandReflectionClass = $container->getReflectionClass($commandClassName);
            if (null === $commandReflectionClass) {
                continue;
            }

            $annotation = $annotationReader->getClassAnnotation($commandReflectionClass, CommandHandlerAnnotation::class);
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
        $definition->setClass(CommandRegistry::class);
        $definition->setArgument(0, $commandClassMap);

        $container->setDefinition(CommandRegistry::class, $definition);
    }
}
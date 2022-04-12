<?php

namespace BBLDN\CQRS\DependencyInjection\Compiler;

use BBLDN\CQRS\Helper\Context;
use BBLDN\CQRS\QueryBus\QueryBus;
use BBLDN\CQRS\QueryBus\QueryBusImpl;
use BBLDN\CQRS\CommandBus\CommandBus;
use BBLDN\CQRS\CommandBus\CommandBusImpl;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface as CompilerPass;

class RegistryPass implements CompilerPass
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
     */
    private function definitionQueryBus(ContainerBuilder $container): void
    {
        $definition = new Definition();
        $definition->setLazy(true);
        $definition->setClass(QueryBusImpl::class);
        $definition->setTags([$this->context->getQueryBusTag()]);

        $definition->setArgument(0, '@kernel');
        $definition->setArgument(1, $this->context->getQueryRegistryTag());

        $container->setDefinition(QueryBus::class, $definition);
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    private function definitionCommandBus(ContainerBuilder $container): void
    {
        $definition = new Definition();
        $definition->setLazy(true);
        $definition->setClass(CommandBus::class);
        $definition->setTags([$this->context->getCommandBusTag()]);

        $definition->setArgument(0, '@kernel');
        $definition->setArgument(1, $this->context->getCommandRegistryTag());

        $container->setDefinition(CommandBusImpl::class, $definition);
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        $this->definitionQueryBus($container);
        $this->definitionCommandBus($container);
    }
}
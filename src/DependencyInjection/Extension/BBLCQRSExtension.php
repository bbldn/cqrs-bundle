<?php

namespace BBLDN\CQRS\DependencyInjection\Extension;

use BBLDN\CQRS\Helper\Context;
use BBLDN\CQRS\QueryBus\Query;
use BBLDN\CQRS\QueryBus\QueryBus;
use BBLDN\CQRS\CommandBus\Command;
use BBLDN\CQRS\QueryBus\QueryBusImpl;
use BBLDN\CQRS\CommandBus\CommandBus;
use BBLDN\CQRS\QueryBus\QueryRegistry;
use BBLDN\CQRS\CommandBus\CommandBusImpl;
use BBLDN\CQRS\CommandBus\CommandRegistry;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class BBLCQRSExtension implements ExtensionInterface
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
     * @return bool
     */
    public function getNamespace(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function getXsdValidationBasePath(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return 'bbldn.cqrs';
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

        $definition->setArgument(0, new Reference('kernel'));
        $definition->setArgument(1, new Reference($this->context->getQueryRegistryTag()));

        $container->setDefinition(QueryBus::class, $definition);
        $container->setAlias($this->context->getQueryBusTag(), QueryBus::class);
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    private function definitionCommandBus(ContainerBuilder $container): void
    {
        $definition = new Definition();
        $definition->setLazy(true);
        $definition->setClass(CommandBusImpl::class);

        $definition->setArgument(0, new Reference('kernel'));
        $definition->setArgument(1, new Reference($this->context->getCommandRegistryTag()));

        $container->setDefinition(CommandBus::class, $definition);
        $container->setAlias($this->context->getCommandBusTag(), CommandBus::class);
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    private function definitionQueryRegistry(ContainerBuilder $container): void
    {
        $definition = new Definition();
        $definition->setLazy(true);
        $definition->setArgument(0, []);
        $definition->setClass(QueryRegistry::class);

        $container->setDefinition(QueryRegistry::class, $definition);
        $container->setAlias($this->context->getQueryRegistryTag(), QueryRegistry::class);
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    private function definitionCommandRegistry(ContainerBuilder $container): void
    {
        $definition = new Definition();
        $definition->setLazy(true);
        $definition->setArgument(0, []);
        $definition->setClass(CommandRegistry::class);

        $container->setDefinition(CommandRegistry::class, $definition);
        $container->setAlias($this->context->getCommandRegistryTag(), CommandRegistry::class);
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    private function registerForAutoconfiguration(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(Query::class)->addTag($this->context->getQueryTag());
        $container->registerForAutoconfiguration(Command::class)->addTag($this->context->getCommandTag());
    }

    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->definitionQueryBus($container);
        $this->definitionCommandBus($container);
        $this->definitionQueryRegistry($container);
        $this->definitionCommandRegistry($container);
        $this->registerForAutoconfiguration($container);
    }
}
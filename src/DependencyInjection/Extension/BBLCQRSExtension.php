<?php

namespace BBLDN\CQRSBundle\DependencyInjection\Extension;

use BBLDN\CQRS\QueryBus\Query;
use BBLDN\CQRS\QueryBus\QueryBus;
use BBLDN\CQRS\CommandBus\Command;
use BBLDN\CQRS\CommandBus\CommandBus;
use BBLDN\CQRSBundle\QueryBus\QueryBusImpl;
use BBLDN\CQRSBundle\QueryBus\QueryRegistry;
use BBLDN\CQRSBundle\CommandBus\CommandBusImpl;
use BBLDN\CQRSBundle\CommandBus\CommandRegistry;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use BBLDN\CQRSBundle\DependencyInjection\Helper\Context;
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
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function getNamespace(): bool
    {
        return false;
    }

    /**
     * @return false
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
        return $this->context->getExtensionTag();
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

        $definition->setArgument(0, new Reference('service_container'));
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

        $definition->setArgument(0, new Reference('service_container'));
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
<?php

namespace BBLDN\CQRS;

use BBLDN\CQRS\QueryBus\Query;
use BBLDN\CQRS\Helper\Context;
use BBLDN\CQRS\QueryBus\QueryBus;
use BBLDN\CQRS\CommandBus\Command;
use BBLDN\CQRS\QueryBus\QueryBusImpl;
use BBLDN\CQRS\CommandBus\CommandBus;
use BBLDN\CQRS\CommandBus\CommandBusImpl;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BBLDN\CQRS\DependencyInjection\Compiler\QueryRegistryPass;
use BBLDN\CQRS\DependencyInjection\Compiler\CommandRegistryPass;

class CQRSBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     * @return void
     */
    public function build(ContainerBuilder $container): void
    {
        $context = new Context();

        $container->registerForAutoconfiguration(Query::class)->addTag($context->getQueryTag());
        $container->registerForAutoconfiguration(Command::class)->addTag($context->getCommandTag());

        $container->autowire(QueryBus::class, QueryBusImpl::class);
        $container->autowire(CommandBus::class, CommandBusImpl::class);

        $container->addCompilerPass(new QueryRegistryPass($context));
        $container->addCompilerPass(new CommandRegistryPass($context));
    }
}
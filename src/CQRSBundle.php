<?php

namespace BBLDN\CQRS;

use BBLDN\CQRS\QueryBus\Query;
use BBLDN\CQRS\Helper\Context;
use BBLDN\CQRS\CommandBus\Command;
use BBLDN\CQRS\Helper\AnnotationReader;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use BBLDN\CQRS\DependencyInjection\Compiler\RegistryPass;
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
        $annotationReader = new AnnotationReader();

        $container->registerForAutoconfiguration(Query::class)->addTag($context->getQueryTag());
        $container->registerForAutoconfiguration(Command::class)->addTag($context->getCommandTag());

        $container->addCompilerPass(new QueryRegistryPass($context, $annotationReader));
        $container->addCompilerPass(new CommandRegistryPass($context, $annotationReader));
        $container->addCompilerPass(new RegistryPass($context));
    }
}
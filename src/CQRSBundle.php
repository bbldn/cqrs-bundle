<?php

namespace BBLDN\CQRS;

use BBLDN\CQRS\Helper\Context;
use BBLDN\CQRS\Helper\AnnotationReader;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BBLDN\CQRS\DependencyInjection\Compiler\QueryRegistryPass;
use BBLDN\CQRS\DependencyInjection\Extension\BBLCQRSExtension;
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

        $container->registerExtension(new BBLCQRSExtension($context));
        $container->addCompilerPass(new QueryRegistryPass($context, $annotationReader));
        $container->addCompilerPass(new CommandRegistryPass($context, $annotationReader));
    }
}
<?php

namespace BBLDN\CQRSBundle;

use BBLDN\CQRSBundle\Helper\AnnotationReader;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use BBLDN\CQRSBundle\DependencyInjection\Helper\Context;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BBLDN\CQRSBundle\DependencyInjection\Compiler\QueryRegistryPass;
use BBLDN\CQRSBundle\DependencyInjection\Extension\BBLCQRSExtension;
use BBLDN\CQRSBundle\DependencyInjection\Compiler\CommandRegistryPass;

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

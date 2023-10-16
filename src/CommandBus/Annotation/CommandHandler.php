<?php

namespace BBLDN\CQRSBundle\CommandBus\Annotation;

use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @Target("CLASS")
 * @NamedArgumentConstructor
 */
final class CommandHandler
{
    public string $class;

    /**
     * @param string $class
     */
    public function __construct(string $class = '')
    {
        $this->class = $class;
    }
}

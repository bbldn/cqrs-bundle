<?php

namespace BBLDN\CQRSBundle\CommandBus\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class CommandHandler
{
    public string $class;

    /**
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }
}
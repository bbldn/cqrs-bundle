<?php

namespace BBLDN\CQRS\CommandBus\Annotation;

use Attribute;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @Target("CLASS")
 * @NamedArgumentConstructor
 */
#[Attribute(Attribute::TARGET_CLASS)]
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
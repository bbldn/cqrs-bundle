<?php

namespace BBLDN\CQRS\CommandBus\Annotation;

use Attribute;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class CommandHandler
{
    public string $class = '';
}
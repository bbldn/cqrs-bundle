<?php

namespace BBLDN\CQRS\CommandBus\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[Attribute(Attribute::TARGET_CLASS)]
class CommandHandler
{
    public string $class = '';
}
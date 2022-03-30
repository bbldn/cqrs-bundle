<?php

namespace BBLDN\CQRS\CommandBus\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class CommandHandler
{
    public string $class = '';
}
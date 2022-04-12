<?php

namespace BBLDN\CQRS\QueryBus\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[Attribute(Attribute::TARGET_CLASS)]
class QueryHandler
{
    public string $class = '';
}
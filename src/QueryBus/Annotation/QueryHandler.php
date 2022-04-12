<?php

namespace BBLDN\CQRS\QueryBus\Annotation;

use Attribute;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class QueryHandler
{
    public string $class = '';
}
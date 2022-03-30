<?php

namespace BBLDN\CQRS\QueryBus\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class QueryHandler
{
    public string $class = '';
}
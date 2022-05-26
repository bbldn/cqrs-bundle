<?php

namespace BBLDN\CQRSBundle\QueryBus\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class QueryHandler
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
<?php

namespace BBLDN\CQRSBundle\QueryBus\Annotation;

use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @Target("CLASS")
 * @NamedArgumentConstructor
 */
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

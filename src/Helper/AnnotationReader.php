<?php

namespace BBLDN\CQRSBundle\Helper;

use ReflectionClass;
use ReflectionAttribute;

class AnnotationReader
{
    /**
     * @param ReflectionClass $classReflection
     * @param string $annotationName
     * @return mixed
     *
     * @psalm-param class-string $annotationName
     */
    public function getClassAnnotation(ReflectionClass $classReflection, string $annotationName): mixed
    {
        /** @var list<ReflectionAttribute> $result */
        $result = $classReflection->getAttributes($annotationName);
        if (true === key_exists(0, $result)) {
            return $result[0]->newInstance();
        }

        return null;
    }
}
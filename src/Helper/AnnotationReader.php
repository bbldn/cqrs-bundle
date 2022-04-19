<?php

namespace BBLDN\CQRSBundle\Helper;

use ReflectionClass;
use Doctrine\Common\Annotations\AnnotationReader as DoctrineAnnotationReader;

class AnnotationReader
{
    private DoctrineAnnotationReader $annotationReader;

    public function __construct()
    {
        $this->annotationReader = new DoctrineAnnotationReader();
    }

    /**
     * @param ReflectionClass $classReflection
     * @param string $annotationName
     * @return mixed
     *
     * @psalm-param class-string $annotationName
     */
    public function getClassAnnotation(ReflectionClass $classReflection, string $annotationName)
    {
        if (PHP_VERSION_ID >= 80000) {
            /** @var list<\ReflectionAttribute> $result */
            $result = $classReflection->getAttributes($annotationName);
            if (true === key_exists(0, $result)) {
                return $result[0]->newInstance();
            }
        }

        return $this->annotationReader->getClassAnnotation($classReflection, $annotationName);
    }
}
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
        return $this->annotationReader->getClassAnnotation($classReflection, $annotationName);
    }
}

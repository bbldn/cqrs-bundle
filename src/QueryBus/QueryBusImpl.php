<?php

namespace BBLDN\CQRSBundle\QueryBus;

use LogicException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface as Container;

class QueryBusImpl implements QueryBus
{
    private Container $container;

    private QueryRegistry $queryRegistry;

    /**
     * @param Container $container
     * @param QueryRegistry $queryRegistry
     */
    public function __construct(
        Container $container,
        QueryRegistry $queryRegistry
    )
    {
        $this->container = $container;
        $this->queryRegistry = $queryRegistry;
    }

    /**
     * @param object $query
     * @return mixed
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function execute(object $query)
    {
        $queryClassName = get_class($query);
        if (false === $this->queryRegistry->has($queryClassName)) {
            throw new LogicException("Handler for $queryClassName not found");
        }

        /** @var string $queryHandlerClassName */
        $queryHandlerClassName = $this->queryRegistry->get($queryClassName);
        if (false === $this->container->has($queryHandlerClassName)) {
            throw new LogicException("Handler for $queryClassName not found");
        }

        /** @var callable $queryHandler */
        $queryHandler = $this->container->get($queryHandlerClassName);

        return call_user_func($queryHandler, $query);
    }
}
<?php

namespace BBLDN\CQRS\Helper;

class Context
{
    private string $queryTag = 'bbldn.cqrs.query_bus.query';

    private string $commandTag = 'bbldn.cqrs.command_bus.command';


    private string $queryBusTag = 'bbldn.cqrs.query_bus';

    private string $commandBusTag = 'bbldn.cqrs.command_bus';


    private string $queryRegistryTag = 'bbldn.cqrs.query_registry';

    private string $commandRegistryTag = 'bbldn.cqrs.command_registry';

    /**
     * @return string
     */
    public function getQueryTag(): string
    {
        return $this->queryTag;
    }

    /**
     * @return string
     */
    public function getCommandTag(): string
    {
        return $this->commandTag;
    }


    /**
     * @return string
     */
    public function getQueryBusTag(): string
    {
        return $this->queryBusTag;
    }

    /**
     * @return string
     */
    public function getCommandBusTag(): string
    {
        return $this->commandBusTag;
    }


    /**
     * @return string
     */
    public function getQueryRegistryTag(): string
    {
        return $this->queryRegistryTag;
    }

    /**
     * @return string
     */
    public function getCommandRegistryTag(): string
    {
        return $this->commandRegistryTag;
    }
}
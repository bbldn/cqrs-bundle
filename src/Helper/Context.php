<?php

namespace BBLDN\CQRS\Helper;

class Context
{
    private string $queryTag;

    private string $commandTag;

    public function __construct()
    {
        $this->queryTag = 'bbldn.cqrs.query_bus.query';
        $this->commandTag = 'bbldn.cqrs.command_bus.command';
    }

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
}
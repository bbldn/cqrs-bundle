<?php

namespace BBLDN\CQRSBundle\DependencyInjection\Helper;

class Context
{
    private string $extensionTag = 'bbldn.cqrs_bundle';

    private string $queryTag = 'bbldn.cqrs_bundle.query_bus.query';

    private string $commandTag = 'bbldn.cqrs_bundle.command_bus.command';


    private string $queryBusTag = 'bbldn.cqrs_bundle.query_bus';

    private string $commandBusTag = 'bbldn.cqrs_bundle.command_bus';


    private string $queryRegistryTag = 'bbldn.cqrs_bundle.query_registry';

    private string $commandRegistryTag = 'bbldn.cqrs_bundle.command_registry';

    /**
     * @return string
     */
    public function getExtensionTag(): string
    {
        return $this->extensionTag;
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
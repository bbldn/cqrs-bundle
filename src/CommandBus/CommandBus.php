<?php

namespace BBLDN\CQRS\CommandBus;

interface CommandBus
{
    /**
     * @param object $command
     * @return mixed
     */
    public function execute(object $command);
}
<?php

namespace BBLDN\CQRSBundle\CommandBus;

interface CommandBus
{
    /**
     * @param object $command
     * @return mixed
     */
    public function execute(object $command);
}

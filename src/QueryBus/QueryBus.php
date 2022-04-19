<?php

namespace BBLDN\CQRSBundle\QueryBus;

interface QueryBus
{
    /**
     * @param object $query
     * @return mixed
     */
    public function execute(object $query);
}
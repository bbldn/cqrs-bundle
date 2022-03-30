<?php

namespace BBLDN\CQRS\QueryBus;

interface QueryBus
{
    /**
     * @param object $query
     * @return mixed
     */
    public function execute(object $query);
}
<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Rest;

interface RestHandlerInterface
{
    public function get(array $resource);

    public function post(array $resource);

    public function put(array $resource);

    public function delete(array $resource);
}
<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Rest;

interface RestHandlerInterface
{
    public function get();

    public function post();

    public function put();

    public function delete();
}
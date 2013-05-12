<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Rest;

use ADR\Bundle\Symfony2ErlangBundle\Service\Rest\RestHandlerInterface;

abstract class AbstractHandler
{
    protected $request;

    public function handle(array $request)
    {
        $this->request = $request;
        // call_user_func($this->getMethod());
        $method = $this->getMethod();

        return $this->$method();
    }

    protected function getMethod()
    {
        return strtolower($this->request['method']);
    }
}
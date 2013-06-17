<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Rest;

use ADR\Bundle\Symfony2ErlangBundle\Service\Rest\RestHandlerInterface;

abstract class AbstractHandler
{
    protected $request = array();

    public function handle(array $request)
    {
        $this->request = $request;
        // call_user_func($this->getMethod());
        // @TODO: check first if callable, then call_user....
        $method = $this->getMethod();

        return $this->$method();
    }

    protected function getMethod()
    {
        return strtolower($this->request['method']);
    }

    /**
     * Used only in test environment
     *
     * @param array $request
     */
    public function setRequest(array $request)
    {
        $this->request = $request;
    }
}
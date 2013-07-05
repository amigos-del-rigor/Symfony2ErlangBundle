<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Rest;

abstract class AbstractHandler
{
    protected $request = array();

    public function handle(array $request)
    {
        $this->request = $request;

        if (!is_callable(array($this, $this->getMethod()))) {
            throw new \Exception(sprintf('callable %s not found', $this->getMethod()));
        }

        return call_user_func(array($this, $this->getMethod()));
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

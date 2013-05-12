<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Rest;

use ADR\Bundle\Symfony2ErlangBundle\Service\Rest\AbstractHandler;
use ADR\Bundle\Symfony2ErlangBundle\Service\Rest\RestHandlerInterface;

class NoopRestHandler extends AbstractHandler implements RestHandlerInterface
{
    public function get()
    {
        return $this->getResponse('get');
    }

    public function post()
    {
        return $this->getResponse('post');
    }

    public function put()
    {
        return $this->getResponse('put');
    }

    public function delete()
    {
        return $this->getResponse('delete');
    }

    protected function getResponse($method)
    {
        $this->request['status_code'] = 200;
        $this->request['test_data'] = $method.'-ok';

        return $this->request;
    }
}

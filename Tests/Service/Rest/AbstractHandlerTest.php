<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Rest;

use ADR\Bundle\Symfony2ErlangBundle\Service\Rest\AbstractHandler;

class AbstractHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->handler = new FakeHandler();
    }

    public function testHandle()
    {
        $request = array();
        $request['method'] = 'test';
        $response = $this->handler->handle($request);

        $this->assertEquals($response, 'test-ok');
    }

    public function testGetMethod()
    {
        $r = new \ReflectionObject($this->handler);
        $m = $r->getMethod('getMethod');
        $m->setAccessible(true);
        $request = array();
        $request['method'] = 'METHOD';
        $this->handler->setRequest($request);
        $response = $m->invoke($this->handler, $request);

        $this->assertEquals($response, 'method');
    }
}

class FakeHandler extends AbstractHandler
{
    public function test()
    {
        return 'test-ok';
    }
}
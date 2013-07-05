<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Rest;

use ADR\Bundle\Symfony2ErlangBundle\Service\Rest\NoopRestHandler;

class NoopRestHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->handler = new NoopRestHandler();
    }

    public function testGetResponse()
    {
        $r = new \ReflectionObject($this->handler);
        $m = $r->getMethod('getResponse');
        $m->setAccessible(true);

        $response = $m->invoke($this->handler, 'test');
        $this->assertArrayHasKey('status_code', $response);
        $this->assertArrayHasKey('test_data', $response);
        $this->assertEquals($response['status_code'], 200);
        $this->assertEquals($response['test_data'], 'test-ok');
    }
}

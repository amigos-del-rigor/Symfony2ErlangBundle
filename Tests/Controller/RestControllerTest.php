<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Controller;

use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\JsonEncoder;
use ADR\Bundle\Symfony2ErlangBundle\Controller\RestController;
use Symfony\Component\HttpFoundation\Request;

class RestControllerTest extends \PHPUnit_Framework_TestCase
{
    protected $controller;

    public function testCreateResponse()
    {
        $encodedData = json_encode($this->getFakeRequestData());
        $this->controller = new RestController(
            $this->getFakeRequest('GET'),
            $this->getFakeJsonEncoder($encodedData),
            $this->getFakeRestHandler()
        );

        $r = new \ReflectionObject($this->controller);
        $m = $r->getMethod('createResponse');
        $m->setAccessible(true);

        $response = $m->invoke($this->controller, $this->getFakeRequestData());
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);

        $content = $response->getContent();
        $this->assertEquals($content, $encodedData);
    }

    /**
     * Intentional Implementation as Integration test
     * that's why this test is coupled to JsonEncoder
     */
    public function testIndexActionFromRequestAsIntegrationTest()
    {

        $this->controller = new RestController(
            $this->getFakeRequest('GET'),
            new JsonEncoder(),
            $this->getFakeRestHandler()
        );

        $response = $this->controller->indexAction();
        $content = $response->getContent();

        $decodeResponse = json_decode($content, true);

        $this->assertInternalType('array', $decodeResponse);
        $this->assertArrayHasKey("method", $decodeResponse);
        $this->assertArrayHasKey("version", $decodeResponse);
        $this->assertArrayHasKey("type", $decodeResponse);
        $this->assertArrayHasKey("name", $decodeResponse);
        $this->assertArrayHasKey("key", $decodeResponse);
        $this->assertEquals($decodeResponse['version'], 'v1');
        $this->assertEquals($decodeResponse['method'], 'GET');
        $this->assertEquals($decodeResponse['type'], 'defaultType');
        $this->assertEquals($decodeResponse['name'], 'defaultName');
        $this->assertEquals($decodeResponse['key'], 1);

    }

    public function getFakeRequest($type = 'GET')
    {
        $mock = \Mockery::mock('Symfony\Component\HttpFoundation\Request');
        $mock->shouldReceive('getMethod')
            ->andReturn('GET');

        return $mock;
    }

    public function getFakeJsonEncoder($encodedData)
    {
        $mock = \Mockery::mock('ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\EncoderInterface');
        $mock->shouldReceive('encode')
            ->andReturn($encodedData);

        return $mock;
    }

    public function getFakeRestHandler()
    {
        $mock = \Mockery::mock('ADR\Bundle\Symfony2ErlangBundle\Service\Rest\RestHandlerInterface');
        $mock->shouldReceive('handle')
            ->andReturn($this->getFakeRequestData());

        return $mock;
    }

    protected function getFakeRequestData()
    {
        return array(
                'method'    =>  'GET',
                'version'   =>  'v1',
                'type'      =>  'defaultType',
                'name'      =>  'defaultName',
                'key'       =>  1,
                'status_code' => 200,
                'testData'  =>  'fakeOK'
            );
    }

}
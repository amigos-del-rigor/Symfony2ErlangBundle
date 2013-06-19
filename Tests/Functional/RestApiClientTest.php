<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Functional;

use ADR\Bundle\Symfony2ErlangBundle\Tests\Functional\AbstractWebTestCase;

class RestApiClientTest extends AbstractWebTestCase
{
    protected $client;
    protected $restClient;

    public function setUp()
    {
        $this->client =  $this->createClient();
        $this->restClient = $this->getContainer()
                                 ->get('adr_symfony2erlang.channel.manager')
                                 ->getChannel('rest_node0');
    }

    public function testFunctionalCOntainerServicesAreUp()
    {
        $this->assertTrue($this->getContainer()->has('adr_symfony2erlang.channel.manager'));
        $this->assertTrue($this->getContainer()->has('adr_symfony2erlang.channel.peb.encoder'));
        $this->assertTrue($this->getContainer()->has('adr_symfony2erlang.channel.json.encoder'));
        $this->assertTrue($this->getContainer()->has('adr_symfony2_erlang.api.rest.controller'));
        $this->assertTrue($this->getContainer()->has('adr_symfony2_erlang.api.rest.handler.noop'));
    }

    public function testGetRestClient()
    {
        $response = $this->restClient->call($this->getResource('GET'));
        $this->assertResponse($response, 'GET');
    }

    public function testPostRestClient()
    {
        $response = $this->restClient->call($this->getResource('POST'));
        $this->assertResponse($response, 'POST');
    }

    public function testPutRestClient()
    {
        $response = $this->restClient->call($this->getResource('PUT'));
        $this->assertResponse($response, 'PUT');
    }

    public function testDeleteRestClient()
    {
        $response = $this->restClient->call($this->getResource('DELETE'));
        $this->assertResponse($response, 'DELETE');
    }

    protected function assertResponse($response, $method)
    {
        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey("method", $response);
        $this->assertArrayHasKey("version", $response);
        $this->assertArrayHasKey("type", $response);
        $this->assertArrayHasKey("name", $response);
        $this->assertArrayHasKey("key", $response);
        $this->assertEquals($response['version'], 'v1');
        $this->assertEquals($response['method'], $method);
        $this->assertEquals($response['type'], 'defaultType');
        $this->assertEquals($response['name'], 'defaultName');
        $this->assertEquals($response['key'], 1);
    }

    protected function getResource($method)
    {
        return array(
                'method'    =>  $method,
                'version'   =>  'v1',
                'type'      =>  'defaultType',
                'name'      =>  'defaultName',
                'key'       =>  1,
            );
    }

    public function tearDown()
    {
        $this->client = null;
        $this->restClient = null;
    }
}
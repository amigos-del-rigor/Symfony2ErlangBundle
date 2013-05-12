<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Functional;

use ADR\Bundle\Symfony2ErlangBundle\Tests\Functional\BaseTestCase;

class RestApiClientTest extends BaseTestCase
{
    protected $client;

    public function setUp()
    {
        $this->client =  $this->createClient();
    }

    public function testFunctionalCOntainerServicesAreUp()
    {
        $this->assertTrue($this->getContainer()->has('adr_symfony2erlang.channel.manager'));
        $this->assertTrue($this->getContainer()->has('adr_symfony2erlang.channel.peb.encoder'));
        $this->assertTrue($this->getContainer()->has('adr_symfony2erlang.channel.json.encoder'));
        $this->assertTrue($this->getContainer()->has('adr_symfony2_erlang.api.rest.controller'));
        $this->assertTrue($this->getContainer()->has('adr_symfony2_erlang.api.rest.handler.noop'));
    }


    public function testSRestClient()
    {
        $restClient = $this->getContainer()->get('adr_symfony2erlang.channel.manager')->getChannel('rest_node0');

        $resources = array(
                'method'    =>  'POST',
                'version'   =>  'v1',
                'type'      =>  'defaultType',
                'name'      =>  'defaultName',
                'key'       =>  1,
            );

        $response = $restClient->call($resources);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey("method", $response);
        $this->assertArrayHasKey("version", $response);
        $this->assertArrayHasKey("type", $response);
        $this->assertArrayHasKey("name", $response);
        $this->assertArrayHasKey("key", $response);
        $this->assertEquals($response['version'], 'v1');
        $this->assertEquals($response['method'], 'POST');
        $this->assertEquals($response['type'], 'defaultType');
        $this->assertEquals($response['name'], 'defaultName');
        $this->assertEquals($response['key'], 1);
    }
}
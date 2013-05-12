<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Functional;

use ADR\Bundle\Symfony2ErlangBundle\Tests\Functional\BaseTestCase;

class RestControllerTest extends BaseTestCase
{
    public function testFunctionalCOntainerServicesAreUp()
    {
        $this->assertTrue($this->getContainer()->has('adr_symfony2erlang.channel.manager'));
        $this->assertTrue($this->getContainer()->has('adr_symfony2erlang.channel.peb.encoder'));
        $this->assertTrue($this->getContainer()->has('adr_symfony2erlang.channel.json.encoder'));
        $this->assertTrue($this->getContainer()->has('adr_symfony2_erlang.api.rest.controller'));
        $this->assertTrue($this->getContainer()->has('adr_symfony2_erlang.api.rest.handler.noop'));
    }

    public function testGetCallToRestAPI()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/v3/test/var/123');
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey("method", $response);
        $this->assertArrayHasKey("version", $response);
        $this->assertArrayHasKey("type", $response);
        $this->assertArrayHasKey("name", $response);
        $this->assertArrayHasKey("key", $response);
        $this->assertEquals($response['version'], 'v3');
        $this->assertEquals($response['method'], 'GET');
        $this->assertEquals($response['type'], 'test');
        $this->assertEquals($response['name'], 'var');
        $this->assertEquals($response['key'], 123);
    }

    public function testPostCallToRestAPI()
    {
        $this->assertTrue(true);
    }

    public function testPutCallToRestAPI()
    {
        $this->assertTrue(true);

    }

    public function testDeleteCallToRestAPI()
    {
        $this->assertTrue(true);

    }
}

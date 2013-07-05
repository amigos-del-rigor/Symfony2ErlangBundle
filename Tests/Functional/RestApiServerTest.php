<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Functional;

use ADR\Bundle\Symfony2ErlangBundle\Tests\Functional\AbstractWebTestCase;

class RestApiServerTest extends AbstractWebTestCase
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

    public function testGetCallToRestAPI()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/v3/test/var/123');
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponses($response, 'GET');
    }

    public function testPostCallToRestAPI()
    {
        $client = $this->createClient();
        $client->request(
            'POST',
            '/api/v3/test/var/123',
            $this->getFakeRequestData('post')
        );

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertResponses($response, 'POST');
    }

    public function testPutCallToRestAPI()
    {
        $client = $this->createClient();
        $client->request(
            'PUT',
            '/api/v3/test/var/123',
            $this->getFakeRequestData('put')
        );

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertResponses($response, 'PUT');

    }

    public function testDeleteCallToRestAPI()
    {
        $client = $this->createClient();
        $crawler = $client->request('DELETE', '/api/v3/test/var/123');
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponses($response, 'DELETE');

    }

    protected function assertResponses($response, $method)
    {
        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('method', $response);
        $this->assertArrayHasKey('version', $response);
        $this->assertArrayHasKey('type', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('key', $response);
        $this->assertArrayHasKey('test_data', $response);
        $this->assertEquals($response['version'], 'v3');
        $this->assertEquals($response['method'], $method);
        $this->assertEquals($response['type'], 'test');
        $this->assertEquals($response['name'], 'var');
        $this->assertEquals($response['key'], 123);
        $this->assertEquals($response['test_data'],  strtolower($method).'-ok');
    }

    protected function getFakeRequestData($method)
    {
        return array(
                'method'    =>  $method,
                'version'   =>  'v1',
                'type'      =>  'defaultType',
                'name'      =>  'defaultName',
                'key'       =>  1,
                'status_code' => 200,
                'test_data'  =>  $method.'-ok'
            );
    }
}

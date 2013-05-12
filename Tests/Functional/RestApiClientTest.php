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

}
<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractWebTestCase extends WebTestCase
{
    /**
     * @var string
     */
    protected static $class = '\ADR\Bundle\Symfony2ErlangBundle\Tests\Functional\AppKernel';

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;

    /**
     * Setup.
     */
    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    protected function getContainer()
    {
        return $this->client->getContainer();
    }
    /**
     * Teardown.
     */
    public function tearDown()
    {
        $this->client = null;
        parent::tearDown();
    }
}
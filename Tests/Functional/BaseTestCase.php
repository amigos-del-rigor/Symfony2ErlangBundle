<?php
namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Functional;

require_once(__DIR__ . "/AppKernel.php");
use ADR\Bundle\Symfony2ErlangBundle\Tests\Functional\AppKernel;

abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected static $kernel;

    protected $container;

    public function __construct()
    {
      $kernel = new AppKernel("test", true);
      $kernel->boot();
      $this->container = $kernel->getContainer();
      parent::__construct();
    }

    /**
     * Creates a Client.
     *
     * @param array $options An array of options to pass to the createKernel class
     * @param array $server  An array of server parameters
     *
     * @return Client A Client instance
     */
    protected static function createClient(array $options = array(), array $server = array())
    {
        if (null !== static::$kernel) {
            static::$kernel->shutdown();
        }

        static::$kernel = new AppKernel("test", true);
        static::$kernel->boot();

        $client = static::$kernel->getContainer()->get('test.client');
        $client->setServerParameters($server);

        return $client;
    }

    protected function get($service)
    {
      return $this->container->get($service);
    }

    protected function getContainer()
    {
      return $this->container;
    }

    protected function getClient($server = array())
    {
      return $this->getContainer()->get('test.client');
    }
}
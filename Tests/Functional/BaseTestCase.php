<?php
namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Functional;

// require_once(__DIR__ . "/../../../../../../../../app/AppKernel.php");
require_once(__DIR__ . "/AppKernel.php");
use ADR\Bundle\Symfony2ErlangBundle\Tests\Functional\AppKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BaseTestCase extends WebTestCase
{
    // protected static $kernel;

    protected $container;

    // public function __construct()
    // {
    //   $kernel = new AppKernel("test", true);
    //   $kernel->boot();
    //   $this->container = $kernel->getContainer();
    //   parent::__construct();
    // }

    // /**
    //  * Creates a Client.
    //  *
    //  * @param array $options An array of options to pass to the createKernel class
    //  * @param array $server  An array of server parameters
    //  *
    //  * @return Client A Client instance
    //  */
    // protected static function createClient(array $options = array(), array $server = array())
    // {
    //     if (null !== static::$kernel) {
    //         static::$kernel->shutdown();
    //     }

    //     static::$kernel = new AppKernel("test", true);
    //     static::$kernel->boot();

    //     var_dump(static::$kernel->getContainer()->get('test.client'));die();
    //     $client = static::$kernel->getContainer()->get('test.client');
    //     $client = clone $this->getClient();
    //     $client->setServerParameters($server);

    //     return $client;
    // }

    /**
     * Creates a Kernel.
     *
     * Available options:
     *
     *  * environment
     *  * debug
     *
     * @param array $options An array of options
     *
     * @return HttpKernelInterface A HttpKernelInterface instance
     */
    protected static function createKernel(array $options = array())
    {
        return  static::$kernel = new AppKernel("test", true);
    }

    protected function get($service)
    {
      return static::$kernel->getContainer()->get($service);
    }

    protected function getContainer()
    {
      return static::$kernel->getContainer();
    }

    protected function getClient($server = array())
    {
      return static::$kernel->getContainer()->get('test.client');
    }
}
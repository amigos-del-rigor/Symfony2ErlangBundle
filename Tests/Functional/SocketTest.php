<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Functional;

use ADR\Bundle\Symfony2ErlangBundle\Tests\Functional\AbstractWebTestCase;
use Symfony\Component\Process\Process;

class SocketTest extends AbstractWebTestCase
{

    protected $host = '127.0.0.1';

    /**
     * @var server port
     */
    protected $port = 10000;

    /**
     * @var Process
     */
    protected $process;

    protected $socketClient;

    public function setUp()
    {
        parent::setUp();
        $this->startServer();
        sleep(1);
        $this->socketClient = $this->getContainer()->get('adr_symfony2erlang.channel.manager')->getChannel('socket_node0');
    }

    /**
     * @large
     */
    public function testSocketClientOnSingleData()
    {
        for ($i=0; $i < 1000; $i++) {
            $input = sprintf('sending message %s', $i);
            $response =$this->socketClient->call($input);
            $this->assertInternalType('array', $response);
            $message = array_pop($response);
            $this->assertContains($input, $message);
        }

        $this->socketClient->closeChannel();
    }

    /**
     * @large
     */
    public function testSocketClientOnComplexData()
    {
        $resource = array(
            'module'=> 'module',
            'function' => 'function'
        );

        for ($i=0; $i < 100; $i++) {
            $message = sprintf('sending message %s', $i);
            $data = array('data' => $message);
            $parameters = array('parameters' => $i);
            $response =$this->socketClient->call($resource, $data, $parameters);

            $this->assertInternalType('array', $response);
            $this->assertArrayHasKey('module', $response);
            $this->assertArrayHasKey('function', $response);
            $this->assertArrayHasKey('data', $response);
            $this->assertArrayHasKey('parameters', $response);

            $this->assertEquals('module', $response['module']);
            $this->assertEquals('function', $response['function']);
            $this->assertEquals($message, $response['data']);
            $this->assertEquals($i, $response['parameters']);
        }

        $this->socketClient->closeChannel();
    }

    /**
     * Starts the test server.
     */
    public function startServer()
    {
        if ($this->process) {
            $this->process->stop();
        }

        $command = sprintf(
            'php Tests/Service/Socket/SocketServer.php %s %s',
            $this->host,
            $this->port
        );

        $this->process = new Process($command);
        $this->process->start();

        if (!$this->process->isRunning()) {
            $this->fail('Could not start webserver');
        }
    }

    public function tearDown()
    {
        $this->socket = null;
        if ($this->process) {
            $this->process->stop();
        }

        parent::tearDown();
    }
}

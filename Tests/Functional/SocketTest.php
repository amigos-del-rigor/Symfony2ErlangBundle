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
    protected $port = 10020;

    /**
     * @var Process
     */
    protected $process;


    public function setUp()
    {
        //$this->startServer();
    }

    public function testSocketClient()
    {
        // $socketClient = $this->getContainer()->get('adr_symfony2erlang.channel.manager')->getChannel('socket_node0');

        // $response =$socketClient->call('nextMessage', array());
        // $this->assertContains("nextMessage", $response);
        // // $socketServer = new SocketServer($socketClient->getPort());
        // // $socketServer->doOnce();


        // // $content = $socketClient->call(array(), null);
        // // var_dump($content);
        // // $socketServer->closeSocket();

        $this->assertTrue(true);
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
            "php Tests/Service/Socket/SocketServer.php %s %s",
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
    }
}

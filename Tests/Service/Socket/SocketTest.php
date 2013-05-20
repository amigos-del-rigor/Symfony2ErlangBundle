<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Socket;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\Socket;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\JsonEncoder;
use ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Socket\SocketServerTest;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\NoopEncoder;

use Symfony\Component\Process\Process;

class SocketTest extends SocketServerTest
{
    protected $buffer;


    // public function testBasicConnectionToSocketServer()
    // {
    //     $this->startServer();
    //     $process = new Process(sprintf('telnet %s %s', $this->address, $this->port));
    //     $process->start();

    //     $process->wait(function ($type, $buffer) {
    //         $this->buffer .=$buffer;
    //     });

    //     $this->assertContains(sprintf("Connected to %s", $this->address), $this->buffer);

    //     $this->stopServer();
    // }

    /**
     * @large
     *
     * @group long
     * @return [type] [description]
     */
    public function testServerOnLongPooling()
    {
        $this->startServer();

        $encoder = new NoopEncoder();
        $socket = new Socket($encoder);
        $socket->setChannelName('testChannel');
        $socket->setHost('127.0.0.1');
        $socket->setPort(10020);
        // $response =$socket->call('test', array());

        // var_dump($this->checkIsRunning());
        $response =$socket->call('shutdown', array());
        // var_dump($this->checkIsRunning());
        $this->assertContains("Welcome to test php socket server", $response);

        $this->stopServer();
    }
}

<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Socket;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\Socket;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\JsonEncoder;
use ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Socket\SocketServerTest;

use Symfony\Component\Process\Process;

class SocketTest extends SocketServerTest
{
    protected $buffer;

    public function testBasicConnectionToSocketServer()
    {
        $this->startServer();

        $process = new Process('telnet 192.168.1.104 10001');
        $process->start();

        $process->wait(function ($type, $buffer) {
            $this->buffer .=$buffer;
        });

        $this->assertTrue(strrpos($this->buffer, "Connected to 192.168.1.104") > 0);

        $this->stopServer();
    }
}

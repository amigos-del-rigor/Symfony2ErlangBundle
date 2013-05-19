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
 // Stop here and mark this test as incomplete.
        // $this->markTestIncomplete(
        //   'This test has not been implemented yet.'
        // );
        $this->startServer();
        $process = new Process(sprintf('telnet %s %s', $this->address, $this->port));
        $process->start();

        $process->wait(function ($type, $buffer) {
            $this->buffer .=$buffer;
        });

        $this->assertTrue(strrpos($this->buffer, sprintf("Connected to %s", $this->address)) > 0);

        $this->stopServer();
    }
}

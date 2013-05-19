<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Socket;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\Socket;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\JsonEncoder;
use ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Socket\SocketServerTest;

use Symfony\Component\Process\Process;

class SocketTest extends SocketServerTest
{
    protected $buffer;

    /**
     * @large
     *
     * @group long
     * @return [type] [description]
     */
    public function testBasicConnectionToSocketServer()
    {
        $this->startServer();
        $process = new Process(sprintf('telnet %s %s', $this->address, $this->port));
        $process->start();

        $process->wait(function ($type, $buffer) {
            $this->buffer .=$buffer;
        });

        $this->assertContains(sprintf("Connected to %s", $this->address), $this->buffer);

        $this->stopServer();
    }


    public function testServerOnLongPooling()
    {
        $this->startServer();

        for ($i=0; $i < 1; $i++) {
            $command = 'php Tests/Service/Socket/SocketClient.php';
            $this->process = new Process($command);
            $this->process->start();

            $this->process->wait(function ($type, $buffer) {
                $this->buffer .=$buffer;
            });

            $this->assertContains("Welcome to test php socket server", $this->buffer);
        }
        $this->stopServer();

    }
}

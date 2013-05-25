<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Socket;

use Symfony\Component\Process\Process;

abstract class SocketServerProcess extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Process
     */
    protected $process;

    /**
     * Starts the test server.
     */
    public function startServer($host, $port)
    {
        if ($this->process) {
            $this->process->stop();
        }

        $command = sprintf(
            "php Tests/Service/Socket/SocketServer.php %s %s", $host, $port
        );

        $this->process = new Process($command);
        $this->process->start();

        if (!$this->process->isRunning()) {
            $this->fail('Could not start webserver');
        }
    }

    public function stopServer()
    {
        if ($this->process) {
            $this->process->stop();
        }
    }

    /**
     * Kills the server gracefully.
     */
    public function __destruct()
    {
        $this->stopServer();
    }
}
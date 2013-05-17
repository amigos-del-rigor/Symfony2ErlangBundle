<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Socket;

use Symfony\Component\Process\Process;

abstract class SocketServerTest extends \PHPUnit_Framework_TestCase
{

    protected $address = '192.168.1.104';

    /**
     * @var server port
     */
    protected $port = 10001;

    /**
     * @var Process
     */
    protected $process;

    protected $bufferLenght = 2048;
    /**
     * Starts the test server.
     *
     * @param string $router Router script.
     * @param int $port Server port.
     */
    public function startServer($port = 0)
    {
        if ($this->process) {
            $this->process->stop();
        }

        $command = 'php Tests/Service/Socket/SocketServer.php';
        $this->process = new Process($command);
        $this->process->start();

        if (!$this->process->isRunning()) {
            $this->fail('Could not start webserver');
        }
    }

    /**
     * Stops the server.
     */
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

    public function createUrl($path)
    {
        return sprintf('http://127.0.0.1:%d%s', $this->port, $path);
    }
}
<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Socket;

use Symfony\Component\Process\Process;

abstract class SocketServerTest extends \PHPUnit_Framework_TestCase
{

    protected $address = '127.0.0.1';

    /**
     * @var server port
     */
    protected $port = 10020;

    /**
     * @var Process
     */
    protected $process;

    /**
     * @var integer Buffer lenght
     */
    protected $bufferLenght = 2048;

    /**
     * Starts the test server.
     *
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

    public function checkIsRunning()
    {
        return $this->process->isRunning();
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
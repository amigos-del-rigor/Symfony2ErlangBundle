<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Socket;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\Socket;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\JsonEncoder;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\NoopEncoder;
use ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Socket\SocketServerProcess;

use Symfony\Component\Process\Process;

class SocketTest extends SocketServerProcess
{
    protected $buffer;
    protected $socket;

    protected $host = '127.0.0.1';

    /**
     * @var server port
     */
    protected $port = 10015;

    public function setUp()
    {
        if (!extension_loaded('sockets')) {
            $this->markTestSkipped('You need the php socket library to run these tests');
        }

        $this->startServer($this->host, $this->port);

        $encoder = new NoopEncoder();
        $this->socket = new Socket($encoder);
        $this->socket->setChannelName('testChannel');
        $this->socket->setHost($this->host);
        $this->socket->setPort($this->port);
    }

    /**
     * @large
     *
     * @group long
     * @return [type] [description]
     */
    public function testOnOpenChannelOverServer()
    {
        $response =$this->socket->call('test', array());
        $this->assertContains('test', $response);

        $response =$this->socket->call('nextMessage', array());
        $this->assertContains('nextMessage', $response);

        $response =$this->socket->call('packet3', array());
        $this->assertContains('packet3', $response);
    }

    public function tearDown()
    {
        $this->socket = null;
        $this->stopServer();
    }
}

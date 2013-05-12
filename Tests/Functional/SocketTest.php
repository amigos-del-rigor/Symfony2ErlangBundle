<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Functional;

use ADR\Bundle\Symfony2ErlangBundle\Tests\Functional\BaseTestCase;

class SocketTest extends BaseTestCase
{
    protected $client;

    public function setUp()
    {
        $this->client =  $this->createClient();
    }

    public function testFunctionalCOntainerServicesAreUp()
    {
        $this->assertTrue($this->getContainer()->has('adr_symfony2erlang.channel.manager'));
        $this->assertTrue($this->getContainer()->has('adr_symfony2erlang.channel.peb.encoder'));
        $this->assertTrue($this->getContainer()->has('adr_symfony2erlang.channel.json.encoder'));
        $this->assertTrue($this->getContainer()->has('adr_symfony2_erlang.api.rest.controller'));
        $this->assertTrue($this->getContainer()->has('adr_symfony2_erlang.api.rest.handler.noop'));
    }

    //@TODO: Needs to think more about this
    //better to implementit over erlang rest side
    //Has no sense to fire socket server on test process
    //
    public function testSocketClient()
    {
        $socketClient = $this->getContainer()->get('adr_symfony2erlang.channel.manager')->getChannel('socket_node0');
        // $socketServer = new SocketServer($socketClient->getPort());
        // $socketServer->doOnce();


        // $content = $socketClient->call(array(), null);
        // var_dump($content);
        // $socketServer->closeSocket();

        $this->assertTrue(true);
    }
}

class SocketServer
{

    //any Ip connection
    protected $address = 0;
    protected $port = 4545;
    protected $long = 2048;
    protected $timeout = 2;
    protected $socket;

    public function __construct($port)
    {
        $this->port = $port;
    }

    public function doOnce()
    {
        $this->createSocket();
        $this->onLoop();
        $this->closeSocket();
    }

    public function createSocket()
    {
        $this->socket = socket_create(AF_INET,SOCK_STREAM,0);

        socket_bind($this->socket,  $this->address, $this->port);
        socket_listen($this->socket );
    }

    public function onLoop()
    {
        $initTime = time();
        for ($i=0; $i < 10000; $i++) {
            // echo "on loop:".$i."\n";
            // $client = socket_accept($this->socket);
            // $buffer = socket_read($cliente, $this->long);

            // socket_write($cliente, $this->getContent().$buffer);
            // socket_close($cliente);
        }

    }

    protected function getContent()
    {
        return  "Hello";
    }

    public function closeSocket()
    {
        socket_close($this->socket);
    }
}
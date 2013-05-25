<?php
namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Socket;
set_time_limit(0);

class SocketServer
{
    protected $host;
    protected $port;

    public function __construct($host = '127.0.0.1', $port = '10000')
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function start()
    {
        // $host = '127.0.0.1';
        // $port = '10003';
        // create socket
        $socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
        $result = socket_bind($socket, $this->host, $this->port) or die("Could not bind to socket\n");
        $result = socket_listen($socket, 3) or die("Could not set up socket listener\n");

        // accept incoming connections and spawn another socket to handle communication
        while(true)
        {
            $spawn = socket_accept($socket) or die("Could not accept incoming connection\n");

            while(true) {
                $input = socket_read($spawn, 30) or die("Could not read input\n");

                socket_write($spawn, $input, strlen ($input)) or die("Could not write output\n");

            }

            socket_close($spawn);
        }
        socket_close($socket);
    }
}

if (!isset($argv[1]) || !isset($argv[2])) {
    throw new \Exception('Socket server needs to be called as: php SocketServer 127.0.0.1 10000');
}

$altSocketServer = new SocketServer($argv[1], $argv[2]);
$altSocketServer->start();
<?php
namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Socket;
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

class SocketServer
{
    protected $address = '127.0.0.1';
    protected $port = 10001;
    protected $socket;

    public function start()
    {
        $this->socketInit();
        $this->socketBind();
        $this->socketListen();

        do {
            if (($msgsock = socket_accept($this->socket)) === false) {
                throw new \Exception(sprintf("Failure On socket_accept(), error: %s", $this->getSocketError()));
                break;
            }

            $msg = "\nWelcome to test php socket server. \n" .
                "exit with 'quit'. To close server just write'shutdown'.\n";
            socket_write($msgsock, $msg, strlen($msg));

            do {
                if (false === ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ))) {
                    throw new \Exception(sprintf("Failure On socket_read(), error: %s", $this->getSocketError($msgsock)));
                    break 2;
                }
                if (!$buf = trim($buf)) {
                    continue;
                }
                if ($buf == 'quit') {
                    break;
                }
                if ($buf == 'shutdown') {
                    socket_close($msgsock);
                    break 2;
                }

                $response = sprintf("Socket server says: %s", $buf);
                socket_write($msgsock, $response, strlen($response));
                echo "$response\n";

            } while (true);
            socket_close($msgsock);

        } while (true);

        socket_close($this->socket);
    }

    protected function socketInit()
    {
        if (($this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
            throw new \Exception(sprintf("Failure On socket_create(), error: %s", $this->getSocketError(null)));
        }
    }

    protected function socketBind()
    {
        if (socket_bind($this->socket, $this->address, $this->port) === false) {
            throw new \Exception(sprintf("Failure On socket_bind(), error: %s", $this->getSocketError()));
        }
    }

    protected function socketListen()
    {
        if (socket_listen($this->socket, 5) === false) {
            throw new \Exception(sprintf("Failure On socket_listen(), error: %s", $this->getSocketError()));
        }
    }

    protected function getSocketError($parameter = 'socket')
    {
        $socket = ($parameter === 'socket') ? $this->socket : $parameter;

        return socket_strerror(socket_last_error($socket));
    }
}

$server = new SocketServer();
$server->start();


?>
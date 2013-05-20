<?php
namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Socket;
// error_reporting(E_ALL);
set_time_limit(0);
// ob_implicit_flush();

class AltSocketServer
{
    public function start()
    {
        $host = "127.0.0.1";
        $port = 10055;

        // create socket
        $socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
        $result = socket_bind($socket, $host, $port) or die("Could not bind to socket\n");
        $result = socket_listen($socket, 3) or die("Could not set up socket listener\n");

        // accept incoming connections and spawn another socket to handle communication
        while(true)
        {
            $spawn = socket_accept($socket) or die("Could not accept incoming connection\n");

            while(true) {
                echo "before read \n";
                $input = socket_read($spawn, 30) or die("Could not read input\n");
                //$bytes = socket_recv($spawn, $input, 30, MSG_DONTWAIT);
                //var_dump($bytes);
                $input = trim($input);
                if ($input == 'close') {
                    var_dump($input);
                    $output = $this->ensureResponse($input);
                    socket_write($spawn, $output, strlen ($output)) or die("Could not write output\n");
                    socket_close($spawn);
                    break 2;
                }
                $input .="\n";
                //var_dump($input);
                // reverse client input and send back
                $output = $this->ensureResponse($input);
                echo "before write \n";
                socket_write($spawn, $output, strlen ($output)) or die("Could not write output\n");

            }

            //socket_close($spawn);
        }
        socket_close($socket);
    }

    public function ensureResponse($response)
    {
        while (strlen($response) < 30) {
            $response .= '.';
        }

        return $response;
    }
}

$altSocketServer = new AltSocketServer();
$altSocketServer->start();
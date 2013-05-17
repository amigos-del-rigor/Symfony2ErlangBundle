<?php
namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Socket;
error_reporting(E_ALL);
set_time_limit(0);

/* Activar el volcado de salida implícito, así veremos lo que estamo obteniendo mientras llega. */
ob_implicit_flush();

class SocketServer
{
    protected $address = '192.168.1.104';
    protected $port = 10001;

    public function start()
    {
        if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
            echo "socket_create() falló: razón: " . socket_strerror(socket_last_error()) . "\n";
        }

        if (socket_bind($sock, $this->address, $this->port) === false) {
            echo "socket_bind() falló: razón: " . socket_strerror(socket_last_error($sock)) . "\n";
        }

        if (socket_listen($sock, 5) === false) {
            echo "socket_listen() falló: razón: " . socket_strerror(socket_last_error($sock)) . "\n";
        }

        do {
            if (($msgsock = socket_accept($sock)) === false) {
                echo "socket_accept() falló: razón: " . socket_strerror(socket_last_error($sock)) . "\n";
                break;
            }
            /* Enviar instrucciones. */
            $msg = "\nBienvenido al Servidor De Prueba de PHP. \n" .
                "Para salir, escriba 'quit'. Para cerrar el servidor escriba 'shutdown'.\n";
            socket_write($msgsock, $msg, strlen($msg));

            do {
                if (false === ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ))) {
                    echo "socket_read() falló: razón: " . socket_strerror(socket_last_error($msgsock)) . "\n";
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

                $talkback = "PHP: Usted dijo '$buf'.\n";
                socket_write($msgsock, $talkback, strlen($talkback));
                echo "$buf\n";

            } while (true);
            socket_close($msgsock);

        } while (true);

        socket_close($sock);
    }

}

$server = new SocketServer();
$server->start();


?>
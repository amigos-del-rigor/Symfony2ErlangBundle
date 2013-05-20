<?php
namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Socket;
// error_reporting(E_ALL);
set_time_limit(0);
// ob_implicit_flush();

class AltSocketClient
{

    public function start()
    {
        $host = "127.0.0.1";
        $port = 10055;
        $message = 'okis1'."\n";
        $response = '';

        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp'));

        socket_connect($socket,$host,$port);
        echo $message."\n";
        $message = $this->ensureResponse($message);
        $status = socket_sendto($socket,$message, strlen($message), MSG_EOF, $host, $port);
        var_dump($status);
        if ($status == -1)
        {
            $error = socket_strerror(socket_last_error());
            throw new Exception(__FUNCTION__ .' - Unable to connect to spamd on '. $host . ':' . $port . '.  The error message: ' . $error);
        }

       socket_set_option($socket,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>0, "usec"=>10));

        // if (false !== ($bytes = socket_recv($socket, $response, 30, MSG_DONTWAIT))) {
        //     echo "Leídos $bytes bytes desde socket_recv(). Cerrando el socket...";
        // }
        // echo "Response1 $response \n";

       //, PHP_NORMAL_READ
        while ($status = socket_read($socket,30))
        {
            $response .= $status;
            echo "Response1 $response \n";
        }

        $message = 'test2'."\n";
        echo $message."\n";
        $message = $this->ensureResponse($message);
        $status = socket_sendto($socket,$message, strlen($message), MSG_EOF, $host, $port);
        var_dump($status);
        if ($status == -1)
        {
            $error = socket_strerror(socket_last_error());
            throw new Exception(__FUNCTION__ .' - Unable to connect to spamd on '. $host . ':' . $port . '.  The error message: ' . $error);
        }
        socket_set_option($socket,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>0, "usec"=>10));
        $response='';
        while ($status = socket_read($socket,30))
        {
            $response .= $status;
            echo "Response2 $response \n";
        }
        $message = 'close';
        echo $message."\n";
        $status = socket_sendto($socket,$message, strlen($message), MSG_EOF, $host, $port);
        if ($status == -1)
        {
            $error = socket_strerror(socket_last_error());
            throw new Exception(__FUNCTION__ .' - Unable to connect to spamd on '. $host . ':' . $port . '.  The error message: ' . $error);
        }

        $response='';
        while ($status = socket_read($socket,30))
        {
            var_dump($status);
            $response .= $status;
            echo "Response3 $response \n";
        }

        return $response;
    }

    public function ensureResponse($response)
    {
        while (strlen($response) < 30) {
            $response .= '.';
        }

        return $response;
    }
}

$altSocketClient = new AltSocketClient();
var_dump($altSocketClient->start());
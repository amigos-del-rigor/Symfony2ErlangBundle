<?php
namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Socket;
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

require_once(__DIR__ . "/../../../Service/Encoder/EncoderInterface.php");
require_once(__DIR__ . "/../../../Service/Encoder/NoopEncoder.php");
require_once(__DIR__ . "/../../../Service/Plugin/Socket.php");
require_once(__DIR__ . "/../../../Service/Plugin/ChannelInterface.php");

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\Socket;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\NoopEncoder;

class SocketClient
{
    protected $address = '127.0.0.1';
    protected $port = 10020;
    protected $socket;

    public function start()
    {
        $encoder = new NoopEncoder();
        $socket = new Socket($encoder);
        $socket->setChannelName('testChannel');
        $socket->setHost($this->address);
        $socket->setPort($this->port);

        $response =$socket->call('shutdown', array());
        var_dump($response);
        //$response =$socket->call('shutdown', array());
        // var_dump($response);
        //

        $socket->closeChannel();

    }
}

$socketClient = new SocketClient();
$socketClient->start();
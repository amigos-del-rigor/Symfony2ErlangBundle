<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Plugin;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\ChannelInterface;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\EncoderInterface;

class Socket implements ChannelInterface
{
    protected $channelName;
    protected $host;
    protected $port;
    protected $encoder;
    protected $bufferLenght = 2048;


    public function __construct(EncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function call($resource, $data, $params = null) {

        $socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);

//         if (!socket_connect($socket,$this->host,$this->port)) {
//             throw new \Exception(sprintf('Connection  %s failure, on Socket Server Node: %s:%s', $this->channelName, $this->host, $this->port ));
//         }

        echo "Successful conection \n\n";

        socket_write($socket,'resdonse');

        $response = '';
        // while($output = socket_read( $socket, $this->bufferLenght)){
        //     echo "</br>".$output;
        //     $response .= $output;
        // }

        socket_close($socket);

        return $this->encoder->decode($response);
    }

    public function getChannelName()
    {
        return $this->channelName;
    }

    public function setChannelName($channelName)
    {
        return $this->channelName = $channelName;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }
}
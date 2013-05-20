<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Plugin;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\ChannelInterface;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\EncoderInterface;

//implements ChannelInterface
class Socket
{
    protected $channelName;
    protected $host;
    protected $port;
    protected $encoder;
    protected $bufferLenght = 2048;
    protected $socket;


    public function __construct(EncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function call($resource, $data, $params = null) {

        if(!$this->socket) {
            $this->openChannel();
        }

        socket_write($this->socket, $resource);
        $output = socket_read( $this->socket, $this->bufferLenght);

        return $this->encoder->decode($output);

    }

    public function openChannel()
    {
        $this->socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);

        if (!socket_connect($this->socket, $this->host, $this->port)) {
            throw new \Exception(sprintf('Connection  %s failure, on Socket Server Node: %s:%s', $this->channelName, $this->host, $this->port ));
        }
    }

    public function closeChannel()
    {
         socket_close($this->socket);
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
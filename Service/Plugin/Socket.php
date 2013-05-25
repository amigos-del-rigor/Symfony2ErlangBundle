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
    protected $socket;


    public function __construct(EncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function call($resource, $data, $params = null) {

        if(!$this->socket) {
            $this->openChannel();
        }

        $status = socket_sendto($this->socket, $resource, strlen($resource), MSG_EOF, $this->host, $this->port);

        $output = '';
        socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, array("sec"=>0, "usec"=>10));
        while ($status = socket_read($this->socket, $this->bufferLenght))
        {
            $output .= $status;
        }

        return $this->encoder->decode($output);

    }

    public function openChannel()
    {
        $this->socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);

        try {
            $this->connect();
        } catch (\Exception $e) {
            //fix to avoid connection_refused at first try
            sleep(1);
            if (!$this->connect()) {
                throw new \Exception(sprintf('Connection  %s failure, on Socket Server Node: %s:%s. Failed Reason: %s', $this->channelName, $this->host, $this->port , socket_strerror(socket_last_error())));
            }
        }
    }

    protected function connect()
    {
        return socket_connect($this->socket, $this->host, $this->port);
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
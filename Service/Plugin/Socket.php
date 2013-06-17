<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Plugin;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\ChannelInterface;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\EncoderInterface;

class Socket implements ChannelInterface
{
    /**
     * Channel Name Definition
     * @var string
     */
    protected $channelName;

    /**
     * @var EncoderInterface
     */
    protected $encoder;

    /**
     * Host Url
     * @var string
     */
    protected $host;

    /**
     * Destination Port
     * @var integer
     */
    protected $port;

    /**
     * @var integer
     */
    protected $bufferLenght = 2048;

    /**
     * Connection Socket
     */
    protected $socket;


    public function __construct(EncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Implemented by default to pass Json encode data as a field on
     * post request
     *
     * @param string $resource
     * @param string $data
     * @param array $params
     *
     * @return array
     */
    public function call($resource, $data = array(), $params = array()) {

        if(!$this->socket) {
            $this->openChannel();
        }

        if (!is_array($resource)) {
            $resource = array($resource);
        }
        $resource = array_merge($resource, $data, $params);
        $input = $this->encoder->encode($resource);
        $status = socket_sendto($this->socket, $input, strlen($input), MSG_EOF, $this->host, $this->port);

        $output = '';
        socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, array("sec"=>0, "usec"=>50));
        while ($status = socket_read($this->socket, $this->bufferLenght))
        {
            $output .= $status;
        }

        return $this->encoder->decode($output);

    }

    /**
     * Open Channel connection to Socket Server
     *
     * $this->socket gets connection when success
     */
    public function openChannel()
    {
        $this->socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);

        try {
            $this->connect();
        } catch (\Exception $e) {
            //fix to avoid connection_refused at first try
            sleep(1);
            if (!$this->connect()) {
                throw new \Exception(
                    sprintf('Connection  %s failure, on Socket Server Node: %s:%s. Failed Reason: %s',
                        $this->channelName, $this->host, $this->port , socket_strerror(socket_last_error())
                    )
                );
            }
        }
    }

    /**
     * Socket Connenction
     *
     * @return Socket Resource
     */
    protected function connect()
    {
        return socket_connect($this->socket, $this->host, $this->port);
    }

    /**
     * Close socket connection
     */
    public function closeChannel()
    {
         socket_close($this->socket);
    }

    /** Channel definition */

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
<?php
namespace ADR\Bundle\Symfony2ErlangBundle\Service\Plugin;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\ChannelInterface;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\EncoderInterface;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RpcAmqp implements ChannelInterface
{
    /**
     * RPCAMQP
     *
     * @var string
     */
    protected $channelName;
    protected $host;
    protected $port;
    protected $user;
    protected $password;
    protected $link;
    protected $amqpChannel;
    protected $callbackQueue;
    protected $response;
    protected $corrId;

    public function __construct(EncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function call($moduleName, $functionName, $params) {
        if(!$this->link) {
            $this->openChannel();
        }

        $this->response = null;
        $this->corrId = uniqid();

        // TODO
//        $encodedParams = $this->encoder->encode($params);
        $encodedParams = $params;

        $msg = new AMQPMessage(
            (string) $encodedParams,
            array('correlation_id' => $this->corrId,
                  'reply_to'       => $this->callbackQueue)
        );
        $this->amqpChannel->basic_publish($msg, '', $moduleName . ':' . $functionName);
        while(!$this->response) {
            $this->amqpChannel->wait();
        }
        //return intval($this->response);
        // TODO
//        return $this->encoder->decode($this->response);
        return $this->response;
    }

    protected function openChannel() {

        $this->link = new AMQPConnection(
            $this->host, $this->port, $this->user, $this->password
        );

        $this->amqpChannel = $this->link->channel();
        list($this->callbackQueue, ,) = $this->amqpChannel->queue_declare("", false, false, true, false);
        $this->amqpChannel->basic_consume(
            $this->callbackQueue, '', false, false, false, false,
            array($this, 'on_response'));
    }

    public function on_response($rep) {
        if($rep->get('correlation_id') == $this->corrId) {
            $this->response = $rep->body;
        }
    }

    public function setChannelName($channelName)
    {
        $this->channelName = $channelName;
    }

    public function getChannelName()
    {
        return $this->channelName;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function setPort($port)
    {
        $this->port = $port;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}
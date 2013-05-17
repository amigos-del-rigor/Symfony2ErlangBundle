<?php
namespace ADR\Bundle\Symfony2ErlangBundle\Service\Plugin;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\ChannelInterface;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\EncoderInterface;

class Peb implements ChannelInterface
{
    protected $channelName;
    protected $node;
    protected $cookie;
    protected $tiemout;
    protected $encoder;
    protected $link = false;
    protected $environment = 'linux';

    public function __construct(EncoderInterface $encoder)
    {
        $this->encoder = $encoder;

        // OS
        $os = php_uname('s');
        if($os == "Darwin") $this->environment = 'mac';
    }

    /**
     * Example call
     * ('ets', 'insert', array('test',array('key9', 'value3')));
     * @param string $moduleName
     * @param string $functionName
     * @param array $params
     * @return array [0][0]=> value
     */
    public function call($moduleName, $functionName, $params)
    {
        if(!$this->link) {
            $this->openChannel();
        }

        if ($functionName === 'insert') {
            $parameters = array("[~a, {~a, ~s}]", $params);
        } elseif($functionName === 'lookup') {
            $parameters = array("[~a, ~a]", $params);
        }

        $message = $this->encoder->encode($parameters, 'encode');
        $result = peb_rpc($moduleName, $functionName, $message, $this->link);

        return $this->encoder->decode($result);
    }

    /**
     * Open Channel connection to erlang Node
     *
     * $this->link gets connection when success
     */
    protected function openChannel() {
        $connectionParams = ($this->environment === 'linux') ? array($this->node, $this->cookie) : array($this->node, $this->cookie, $this->timeout);

        $this->link = $this->getConnection();

        if (!$this->link) {
            throw new \Exception(sprintf('Connection  %s failure, on erlang Node: %s', $this->channelName, $this->node ));
        }
    }

    protected function getConnection()
    {
        if($this->environment === 'linux') {
            return peb_connect($this->node, $this->cookie);
        }

        return peb_connect($this->node, $this->cookie, $this->timeout);
    }

    /**
     * Close Channel connection
     */
    public function closeChannel() {
        peb_close($this->link);
    }

    public function setChannelName($channelName)
    {
        $this->channelName = $channelName;
    }

    public function getChannelName()
    {
        return $this->channelName;
    }

    public function setNode($node)
    {
        $this->node = $node;
    }

    public function setCookie($cookie)
    {
        $this->cookie = $cookie;
    }

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }
}
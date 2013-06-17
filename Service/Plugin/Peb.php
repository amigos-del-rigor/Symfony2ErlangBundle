<?php
namespace ADR\Bundle\Symfony2ErlangBundle\Service\Plugin;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\ChannelInterface;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\EncoderInterface;

class Peb implements ChannelInterface
{
    /**
     * Channel Name Definition
     * @var string
     */
    protected $channelName;

    /**
     * Node Url
     * @var string
     */
    protected $node;

    /**
     * Security passPhrase Cookie
     * @var string
     */
    protected $cookie;

    /**
     * timeout definition
     * @var int
     */
    protected $tiemout;

    /**
     * @var EncoderInterface
     */
    protected $encoder;

    /**
     * Connection link
     * @var boolean|pebConnection
     */
    protected $link = false;

    /**
     * Server Environment
     * @var string
     */
    protected $environment = 'linux';

    public function __construct(EncoderInterface $encoder)
    {
        $this->encoder = $encoder;

        // Used to solve Environment
        if(php_uname('s') == "Darwin") {
            $this->environment = 'mac';
        }
    }

    /**
     * Example call
     * ('ets', 'insert', array('test',array('key9', 'value3')));
     * call('ets', 'insert', array("[~a, {~a, ~s}]", array('test', array('key9', 'value3'))));
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

        if (!isset($params[0])||!isset($params[1])) {
            throw new \Exception("Bad format params", 1);

        }
        $data = array(
            'functionName' => $functionName,
            'structure' => $params[0],
            'params' => $params[1]
        );

        $message = $this->encoder->encode($data);

        $result = $this->rpcCall($moduleName, $functionName, $message);

        return $this->encoder->decode($result);
    }

    /**
     * Open Channel connection to erlang Node
     *
     * $this->link gets connection when success
     */
    protected function openChannel() {

        $this->link = $this->getConnection();

        if (!$this->link) {
            throw new \Exception(sprintf('Connection  %s failure, on erlang Node: %s', $this->channelName, $this->node ));
        }
    }

    /**
     * Makes RPC call on erlang node, in a transparent way
     *
     * @param string $moduleName
     * @param string $functionName
     * @param string $message
     *
     * @return erlang Term
     */
    protected function rpcCall($moduleName, $functionName, $message)
    {
         $result = peb_rpc($moduleName, $functionName, $message, $this->link);
         if ($result === false) {
            throw new \Exception('Bad RPC response, peb_rpc returned false');
         }

         return $result;
    }

    /**
     * Actually PEB extension throws segment violation fault on linux
     * environments when timeout parameter is specified
     *
     * @return PebConnection Link
     */
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
    public function closeChannel()
    {
        peb_close($this->link);
    }

    /** Channel definition */

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
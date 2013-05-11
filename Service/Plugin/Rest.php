<?php
namespace ADR\Bundle\Symfony2ErlangBundle\Service\Plugin;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\ChannelInterface;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\EncoderInterface;
use Guzzle\Http\Client;

class Rest implements ChannelInterface
{
    protected $channelName;
    protected $host;
    protected $port;
    protected $encoder;

    /**
     * http://guzzlephp.org/
     * @param EncoderInterface $encoder [description]
     */
    public function __construct(EncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @TODO: Maybe ChannelInterface is too rigid
     * Implemented to pass Json encode data as a field on
     * post request
     * @param  [type] $resource [description]
     * @param  [type] $data     [description]
     * @param  [type] $params   [description]
     * @return [type]           [description]
     */
    public function call($resource, $data, $params = null) {

        $client = new Client($this->host);
        $request = $client->post('/api', null, array(
            'data' => $this->encoder->encode($data)
        ));
        $response = $request->send();

        // echo $response->getBody();
        // echo $response->getHeader('Content-Length');

        return $this->encoder->decode($response->getBody());
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

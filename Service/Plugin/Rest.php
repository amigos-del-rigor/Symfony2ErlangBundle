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
    protected $resource;

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
    public function call($resource, $data = array(), $params = null)
    {
        $this->resource = $resource;
        $this->data = $data;

        $method = $this->getMethod();
        $response = $this->$method();

        return $this->encoder->decode($response->getBody());
    }

    protected function getMethod()
    {
        return strtolower($this->resource['method']);
    }

    protected function getRequestPath()
    {
        return $this->resource['version'].'/'.
                $this->resource['type'].'/'.
                $this->resource['name'].'/'.
                $this->resource['key'];
    }

    protected function get()
    {
        $client = new Client($this->host);
        $request = $client->get($this->getRequestPath());

        return $request->send();
    }

    protected function post()
    {
        $client = new Client($this->host);

        $request = $client->post($this->getRequestPath(), null, $this->data);

        return $request->send();
    }

    protected function put()
    {
        $client = new Client($this->host);
        $request = $client->put($this->getRequestPath(), null, 'this is the body');

        return $request->send();
    }

    protected function delete()
    {
        $client = new Client($this->host);
        $request = $client->delete($this->getRequestPath());

        return $request->send();
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

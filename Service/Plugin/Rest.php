<?php
namespace ADR\Bundle\Symfony2ErlangBundle\Service\Plugin;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\ChannelInterface;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\EncoderInterface;
use Guzzle\Http\Client;

class Rest implements ChannelInterface
{
    /**
     * Channel Name Definition
     * @var string
     */
    protected $channelName;

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
     * @var EncoderInterface
     */
    protected $encoder;

    /**
     * Destination Resource
     * @var [type]
     */
    protected $resource;

    /**
     * @param EncoderInterface $encoder [description]
     */
    public function __construct(EncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Implemented to pass Json encode data as a field on
     * post request
     *
     * @param string $resource
     * @param string $data
     * @param array $params
     *
     * @return array
     */
    public function call($resource, $data = array(), $params = null)
    {
        $this->resource = $resource;
        $this->data = $data;

        if (!is_callable(array($this, $this->getMethod()))) {
            throw new \Exception(sprintf("callable %s not found", $this->getMethod()));
        }

        $response = call_user_func(array($this, $this->getMethod()));

        return $this->encoder->decode($response->getBody());
    }

    /**
     * Handle Get Call
     *
     * @return Response
     */
    protected function get()
    {
        $client = new Client($this->host);
        $request = $client->get($this->getRequestPath());

        return $request->send();
    }

    /**
     * Handle POST Call
     *
     * @return Response
     */
    protected function post()
    {
        $client = new Client($this->host);
        $request = $client->post($this->getRequestPath(), null, $this->data);

        return $request->send();
    }

    /**
     * Handle PUT Call
     *
     * @return Response
     */
    protected function put()
    {
        $client = new Client($this->host);
        $request = $client->put($this->getRequestPath(), null, 'this is the body');

        return $request->send();
    }

    /**
     * Handle DELETE Call
     *
     * @return Response
     */
    protected function delete()
    {
        $client = new Client($this->host);
        $request = $client->delete($this->getRequestPath());

        return $request->send();
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

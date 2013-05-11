<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service;
use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\ChannelInterface;

Class ChannelManager
{
    /**
     * Array Configured Channels
     * @var array
     */
    protected $channels = array();

    /**
     * Add Channel to ChannelManager Pool
     * @param ChannelInterface $channel
     */
    public function addChannel(ChannelInterface $channel, $id = null)
    {
        $this->channels[$channel->getChannelName()] = $channel;
    }

    /**
     * Get Channel from Pool
     * @param string $name Channel Name
     * @return ChannelInterface
     */
    public function getChannel($name)
    {
        if (!isset($this->channels[$name])) {
            throw new \Exception(sprintf('Channel %s not found', $name));
        }

        return $this->channels[$name];
    }

    /**
     *
     * @param ChannelInterface $channel
     * @param  [type] $id      plugin $id
     * @return [type]          [description]
     */
    protected function getChannelName(ChannelInterface $channel, $id = null)
    {
        return $channel->getName();
    }
}

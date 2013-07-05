<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\ChannelInterface;

/**
 * Abstraction Class to access erlang Channels
 */
Class ChannelManager
{
    /**
     * Array Configured Channels
     *
     * @var array
     */
    protected $channels = array();

    /**
     * Add Channel to ChannelManager Pool
     *
     * @param ChannelInterface $channel
     * @param int $id plugin id
     */
    public function addChannel(ChannelInterface $channel, $id = null)
    {
        $this->channels[$channel->getChannelName()] = $channel;
    }

    /**
     * Get Channel from Pool
     *
     * @param string $name Channel Name
     *
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
     * @param ChannelInterface $channel
     * @param  int $id plugin id
     *
     * @return string
     */
    protected function getChannelName(ChannelInterface $channel, $id = null)
    {
        return $channel->getName();
    }

    /**
     * Used Only on testing environment
     *
     * @return array
     */
    public function getChannels()
    {
        return $this->channels;
    }
}

<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\ChannelInterface;

/**
 * ETS Example to show easy implementation
 */
Class Ets
{
    protected $channel;

    public function __construct(ChannelInterface $channel)
    {
        $this->channel = $channel;
    }

    public function get($etsTable, $key)
    {
        return $this->channel->call(
            'ets', 'lookup', array($etsTable, $key)
        );
    }

    public function set($etsTable, $key, $value)
    {
        return $this->channel->call(
            'ets', 'insert', array($etsTable,array($key, $value))
        );
    }
}

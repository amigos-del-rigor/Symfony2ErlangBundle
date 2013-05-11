<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service;

use ADR\Bundle\Symfony2ErlangBundle\Service\ChannelManager;
use Mockery;

class ChannelManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $channelManager;

    public function setUp()
    {
        $this->channelManager = new ChannelManager();
    }

    public function testAddAndGetChannel()
    {
        $channel = $this->getFakeChannel();
        $this->channelManager->addChannel($channel);

        $outputChannel = $this->channelManager->getChannel('fakeChannel');

        $this->assertEquals($channel, $outputChannel);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Channel Fake not found
     */
    public function testFailOnGetChannel()
    {
        $outputChannel = $this->channelManager->getChannel('Fake');
    }

    protected function getFakeChannel()
    {
        $channel =  Mockery::mock('\ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\ChannelInterface'
        );
        $channel->shouldReceive('getChannelName')->andReturn('fakeChannel');

        return $channel;
    }
}
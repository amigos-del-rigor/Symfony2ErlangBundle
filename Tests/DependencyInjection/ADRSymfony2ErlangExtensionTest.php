<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\DependencyInjection;

use ADR\Bundle\Symfony2ErlangBundle\DependencyInjection\ADRSymfony2ErlangExtension;
use ADR\Bundle\Symfony2ErlangBundle\DependencyInjection\Compiler\PluginsCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Yaml\Yaml;
use Mockery;

class ADRSymfony2ErlangExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = new ContainerBuilder();

        $config = Yaml::parse($this->getBundleConfig());
        $extension = new ADRSymfony2ErlangExtension();
        $extension->load(array($config), $this->container);
        $compilerPass = new PluginsCompilerPass();
        $compilerPass->process($this->container);
    }

    public function testChannelManagerAndServiceDefinitionsAfterCompilerPass()
    {
        $this->assertTrue($this->container->hasParameter('adr_symfony2_erlang.configured.channels'));
        $this->assertTrue($this->container->has('adr_symfony2erlang.channel.manager'));
        $this->assertServices();
    }

    public function testChannelManagerLoads()
    {
        $channelManager = $this->container->get('adr_symfony2erlang.channel.manager');
        $this->assertInstanceOf('ADR\Bundle\Symfony2ErlangBundle\Service\ChannelManager', $channelManager);

        $channels = $channelManager->getChannels();
        $this->assertInternalType('array', $channels);
        $this->assertCount(5, $channels);

        $this->arrayHasKey('peb_node0', $channels);
        $this->arrayHasKey('peb_node1', $channels);
        $this->arrayHasKey('rest_node0', $channels);
        $this->arrayHasKey('rpc_amqp_node0', $channels);
        $this->arrayHasKey('socket_node0', $channels);
    }

    /**
     *@dataProvider channelProvider
     */
    public function testPebChannelFromChannelMannager($channelName, $channelType)
    {
        $pebChannel = $this->container->get('adr_symfony2erlang.channel.manager')->getChannel($channelName);
        $this->assertInstanceOf('ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\\'.$channelType, $pebChannel);

        $this->assertEquals($channelName, $pebChannel->getChannelName());

    }

    public function channelProvider()
    {
        return array(
            array('peb_node0', 'Peb'),
            array('peb_node1', 'Peb'),
            array('rest_node0', 'Rest'),
            array('rpc_amqp_node0', 'RpcAmqp'),
            array('socket_node0', 'Socket')
        );
    }

    public function testContainerAfterCompile()
    {
        $this->container->getCompilerPassConfig()->setOptimizationPasses(array());
        $this->container->getCompilerPassConfig()->setRemovingPasses(array());
        $this->container->compile();

        $this->assertServices();
    }

    protected function assertServices()
    {
        $this->assertTrue($this->container->has('adr_symfony2_erlang.channel.peb.encoder'));
        $this->assertTrue($this->container->has('adr_symfony2_erlang.channel.json.encoder'));
        $this->assertTrue($this->container->has('adr_symfony2_erlang.channel.peb'));
        $this->assertTrue($this->container->has('adr_symfony2_erlang.channel.rest'));
        $this->assertTrue($this->container->has('adr_symfony2_erlang.channel.rpc_amqp'));
        $this->assertTrue($this->container->has('adr_symfony2_erlang.channel.socket'));
        $this->assertTrue($this->container->has('adr_symfony2_erlang.api.rest.controller'));
        $this->assertTrue($this->container->has('adr_symfony2_erlang.api.rest.handler.noop'));
        $this->assertTrue($this->container->has('adr_symfony2_erlang.api.rest.handler'));
    }

    protected function getBundleConfig()
    {
        return <<<'EOF'
channels:
    peb_node0:
        type:   peb
        config:
            node: 'node0@machine'
            cookie: 'abc'
            timeout: 2
    peb_node1:
        type:   peb
        config:
            node: 'node0@127.0.0.1'
            cookie: 'fh38ga00SIUG'
            timeout: 2
    rest_node0:
        type:   rest
        config:
            host: 'http://sf2.erlang.local/app_dev.php/api/'
            port: 80
    rpc_amqp_node0:
        type:  rpc_amqp
        config:
            host: 'localhost'
            port: 5672
            user: 'guest'
            password: 'guest'
    socket_node0:
        type:  socket
        config:
            host: 'sf2.erlang.local'
            port: 5000
EOF;
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->container = null;
    }
}

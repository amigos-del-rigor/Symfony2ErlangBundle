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

class ADRSymfony2ErlangExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected $container;
    protected $extension;

    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $config = Yaml::parse($this->getBundleConfig());
        $this->extension = new ADRSymfony2ErlangExtension();
        $this->extension->load(array($config), $this->container);
    }

    public function testContainerDefinitionArLoadExtension()
    {
        $this->assertTrue($this->container->hasParameter('adr_symfony2_erlang.configured.channels'));

        $this->assertTrue($this->container->has('adr_symfony2_erlang.api.rest.handler'));

        $alias = $this->container->getAlias('adr_symfony2_erlang.api.rest.handler');
        $this->assertEquals(
            $this->container->getDefinition($alias)->getClass(),
            $this->container->getDefinition('adr_symfony2_erlang.api.rest.handler.noop')->getClass()
        );

        $this->assertContains(
            $this->container->getDefinition($alias)->getClass(),
            $this->extension->getClassesToCompile()
        );
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

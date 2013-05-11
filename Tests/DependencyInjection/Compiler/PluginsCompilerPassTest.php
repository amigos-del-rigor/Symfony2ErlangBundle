<?php
/**
 * @author Jordi Llonch <llonch.jordi@gmail.com>
 * @date 26/04/13 23:59
 */

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use ADR\Bundle\Symfony2ErlangBundle\DependencyInjection\Compiler\PluginsCompilerPass;
use Symfony\Component\DependencyInjection\Reference;

class PluginsCompilerPassTestTest extends \PHPUnit_Framework_TestCase
{

    protected $compiler;

    public function setUp()
    {
        $this->compiler = new PluginsCompilerPass();
    }

    public function testProcessTagedPlugins()
    {
        $r = new \ReflectionObject($this->compiler);
        $m = $r->getMethod('processTagedPlugins');
        $m->setAccessible(true);
        $m->invoke($this->compiler, $this->getFakeContainer());

        $m = $r->getMethod('getPlugin');
        $m->setAccessible(true);
        $result = $m->invoke($this->compiler, 'rest');

        $this->assertEquals($result, 'adr_symfony2erlang.channel.rest');
    }

    protected function getFakeContainer()
    {
        $channels = array(
            'adr_symfony2erlang.channel.rest' => array(array('channel'=> 'rest')),
            'adr_symfony2erlang.channel.peb' => array(array('channel'=> 'peb'))
        );
        $container =  \Mockery::mock('\Symfony\Component\DependencyInjection\ContainerBuilder'
        );
        $container->shouldReceive('findTaggedServiceIds')->andReturn($channels);

        return $container;
    }
}

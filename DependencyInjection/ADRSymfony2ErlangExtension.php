<?php

namespace ADR\Bundle\Symfony2ErlangBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

class ADRSymfony2ErlangExtension extends Extension
{
    protected $plugins = array();

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.xml');

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('adr_symfony2_erlang.configured.channels', $config);

        $container->setAlias(
            'adr_symfony2_erlang.api.rest.handler',
            'adr_symfony2_erlang.api.rest.handler.noop'
        );

        $container->setAlias(
            'adr_symfony2_erlang.channel.peb.formatter',
            'adr_symfony2_erlang.channel.peb.formatter.base'
        );

        // Add service classes to the class cache for performance.
        $this->addClassesToCompile(array(
            $container->findDefinition('adr_symfony2_erlang.api.rest.handler')->getClass(),
            $container->findDefinition('adr_symfony2_erlang.channel.peb.formatter')->getClass()
        ));
    }
}

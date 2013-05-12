<?php

namespace ADR\Bundle\Symfony2ErlangBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
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

        //@TODO: Waiting COnfiguration validation
        // $config = $this->processConfiguration($configuration, $configs);
        $config = $configs[0];

        $container->setParameter('adr_symfony2_erlang.configured.channels', $config);

        //@TODO
        $container->setAlias('adr_symfony2_erlang.api.rest.handler', $config['restHandler']);

        // Add service classes to the class cache for performance.
        $this->addClassesToCompile(array(
            $container->findDefinition('adr_symfony2_erlang.api.rest.handler')->getClass()
        ));
    }
}

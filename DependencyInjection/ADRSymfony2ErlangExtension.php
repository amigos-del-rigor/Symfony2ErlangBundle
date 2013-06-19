<?php

namespace ADR\Bundle\Symfony2ErlangBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

class ADRSymfony2ErlangExtension extends Extension
{
    protected $plugins = array();

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader(
            $container, new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('adr_symfony2_erlang.configured.channels', $config);

        //service alias processing
        if (!$container->hasParameter('adr_symfony2_erlang.services')) {
            $container->setParameter(
                'adr_symfony2_erlang.services',array(
                    'api.rest.handler' => 'adr_symfony2_erlang.api.rest.handler.noop'
                )
            );
        }

        $config = $container->getParameter('adr_symfony2_erlang.services');

        foreach ($config as $id => $serviceId) {
            try {
                $container->findDefinition($serviceId);
            } catch(InvalidArgumentException $e) {
                throw new LogicException(sprintf(
                    'The service (%s) specified for (%s) is not defined.',
                    $serviceId, $id)
                );
            }

            $container->setAlias('adr_symfony2_erlang.' . $id, $serviceId);
        }

        // Add service classes to the class cache for performance.
        $this->addClassesToCompile(array(
            $container->findDefinition('adr_symfony2_erlang.api.rest.handler')->getClass()
        ));
    }
}

<?php
/**
 * @author Jordi Llonch <llonch.jordi@gmail.com>
 * @date 24/04/13 13:41
 */

namespace ADR\Bundle\Symfony2ErlangBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;

class PluginsCompilerPass implements CompilerPassInterface
{
    /**
     * Define Channel Configuration
     *
     * @param  ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $configuredChannels = $container->getParameter(
            'adr_symfony2_erlang.configured.channels'
        );

        $this->defineChannelsConfiguration($container, $configuredChannels);
    }

    /**
     * Define Plugin Config Parameters
     *
     * @param ContainerBuilder $container
     * @param array $config
     */
    protected function defineChannelsConfiguration(ContainerBuilder $container, array $config)
    {
        $this->processTagedPlugins($container);
        $definition = new Definition('ADR\Bundle\Symfony2ErlangBundle\Service\ChannelManager');

        if (!isset($config['channels'])) {
            throw new \Exception('Configure adr_symfony2_erlang channel on config.yml', 1);
        }

        foreach ($config['channels'] as $name => $parameters) {

            $pluginId = $this->getPlugin($parameters['type']);

            //@TODO: As new Reference ID
            $channelDefinition = clone $container->getDefinition($pluginId);
            $channelDefinition->addMethodCall('setChannelName', array($name));

            foreach ($parameters['config'] as $key => $event) {
                $channelDefinition->addMethodCall($this->getSetterMethod($key), array($event));
            }
            $definition->addMethodCall('addChannel', array($channelDefinition));
        }

        $container->setDefinition('adr_symfony2erlang.channel.manager', $definition);
    }

    /**
     * Process Plugin Ids by type for better access
     *
     * @param ContainerBuilder $container
     */
    protected function processTagedPlugins(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('erlang.channel') as $id => $attributes) {
            $this->plugins[$attributes[0]['channel']] = $id;
        }
    }

    /**
     * Returns Plugin Id
     *
     * @param string $type
     *
     * @return Plugin object
     */
    protected function getPlugin($type)
    {
        if (!isset($this->plugins[$type])) {
            throw new \Exception (sprintf('plugin %s not found', $type));
        }

        return $this->plugins[$type];
    }

    /**
     * Auxiliar method to preset Definitions
     *
     * @param string $key
     *
     * @return string
     */
    protected function getSetterMethod($key)
    {
        return 'set' . ucfirst($key);
    }
}
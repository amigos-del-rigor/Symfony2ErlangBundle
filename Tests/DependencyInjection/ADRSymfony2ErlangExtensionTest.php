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
use ADR\Bundle\Symfony2ErlangBundle\DependencyInjection\ADRSymfony2ErlangExtension;
use Symfony\Component\DependencyInjection\Reference;

class ADRSymfony2ErlangExtensionTest extends \PHPUnit_Framework_TestCase {

    public function test()
    {
        
    }

    private function getContainer($file, $debug = false)
    {
        $container = new ContainerBuilder(new ParameterBag(array('kernel.debug' => $debug)));
        $container->registerExtension(new ADRSymfony2ErlangExtension());

        $locator = new FileLocator(__DIR__.'/Fixtures');
        $loader = new YamlFileLoader($container, $locator);
        $loader->load($file);

        $container->getCompilerPassConfig()->setOptimizationPasses(array());
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();

        return $container;
    }
}

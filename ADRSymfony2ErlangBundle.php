<?php

namespace ADR\Bundle\Symfony2ErlangBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use ADR\Bundle\Symfony2ErlangBundle\DependencyInjection\Compiler\PluginsCompilerPass;

class ADRSymfony2ErlangBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new PluginsCompilerPass());
    }
}

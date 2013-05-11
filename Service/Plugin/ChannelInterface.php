<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Plugin;

interface ChannelInterface
{
    public function call($moduleName, $functionName, $params);

    public function getChannelName();
}
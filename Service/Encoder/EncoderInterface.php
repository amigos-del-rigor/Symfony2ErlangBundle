<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Encoder;

interface EncoderInterface
{
    public function encode(array $data, $type = null);

    public function decode($data, $type = null);
}
<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Encoder;

Class NoopEncoder implements EncoderInterface
{
    public function encode(array $data, $type = null)
    {
        return $data;
    }

    public function decode($data, $type = 'array')
    {
        return $data;
    }
}
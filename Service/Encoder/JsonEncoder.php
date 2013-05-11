<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Encoder;

Class JsonEncoder implements EncoderInterface
{
    public function encode(array $data, $type = null)
    {
        return json_encode($data);
    }

    public function decode($data, $type = 'array')
    {
        if($type == 'array') {
            return json_decode($data, true);
        }

        return json_decode($data);
    }
}
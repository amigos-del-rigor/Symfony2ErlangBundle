<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Encoder;

Class NoopEncoder implements EncoderInterface
{
    /**
     * @param array $data
     * @param string $type
     *
     * @return mixed
     */
    public function encode(array $data, $type = null)
    {
        return array_pop($data);
    }

    /**
     * @param mixed $data
     * @param string $type
     *
     * @return mixed
     */
    public function decode($data, $type = 'array')
    {
        return $data;
    }
}

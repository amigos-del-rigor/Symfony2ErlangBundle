<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Encoder;

Class JsonEncoder implements EncoderInterface
{
    /**
     * @param array $data
     * @param null  $type
     *
     * @return string
     */
    public function encode(array $data, $type = null)
    {
        return json_encode($data);
    }

    /**
     * @param string $data
     * @param string $type
     *
     * @return mixed
     */
    public function decode($data, $type = 'array')
    {
        if ('array' === $type) {
            return json_decode($data, true);
        }

        return json_decode($data);
    }
}

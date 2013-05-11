<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Encoder;

Class PebEncoder implements EncoderInterface
{
    /**
     * [encode description]
     * @param  array  $data data[0] keys, data[1] params
     * @param  string $type [description]
     * @return [type]       [description]
     */
    public function encode(array $data, $type = 'encode')
    {
        if (!isset($data[0]) || !isset($data[1])){
            throw new \Exception("Bad formated data");
        }

        $values = is_array($data[1])? $data[1]: array($data[1]);

        if($type == 'vencode') {
            return peb_vencode($data[0], array($values));
        }

        return peb_encode($data[0], array($values));
    }

    /**
     * Decode
     *
     * @param  Erlang Term $data resource(64)
     * @param  string $type Vencode version available
     *
     * @return array
     */
    public function decode($data, $type = 'encode')
    {
        $result = ($type == 'vencode')? peb_vdecode($data): peb_decode($data);

        if (!isset($result[0])){
            throw new \Exception("Bad Decoded Structure");
        }

        return $result[0];
    }
}
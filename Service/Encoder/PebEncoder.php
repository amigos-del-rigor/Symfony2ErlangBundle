<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Encoder;

Class PebEncoder implements EncoderInterface
{
    /**
     * @param array  $data
     * @param string $type
     *
     * @return mixed
     */
    public function encode(array $data, $type = 'encode')
    {
        if (!isset($data['functionName']) || !isset($data['params']) || !isset($data['structure'])) {
            throw new \Exception('Bad formatted data Structure');
        }

        return $this->rawEncode(array($data['structure'], $data['params']), $type);
    }

    /**
     * Encode from built parameters
     *
     * @param array  $data [0]=>[***]', [1]=> $params
     * @param string $type variant
     *
     * @return mixed
     */
    protected function rawEncode(array $data, $type)
    {
        if (!isset($data[0]) || !isset($data[1])){
            throw new \Exception('Bad formatted argument and parameters');
        }

        $values = is_array($data[1]) ? $data[1] : array($data[1]);

        if ('vencode' === $type) {
            return peb_vencode($data[0], array($values));
        }

        return peb_encode($data[0], array($values));
    }

    /**
     * Decode
     *
     * @param mixed $data resource(64)
     * @param string $type Vencode version available
     *
     * @return array
     */
    public function decode($data, $type = 'encode')
    {
        $result = ('vencode' === $type) ? peb_vdecode($data) : peb_decode($data);

        if (!isset($result[0])){
            throw new \Exception('Bad Decoded Structure');
        }

        return $result[0];
    }
}

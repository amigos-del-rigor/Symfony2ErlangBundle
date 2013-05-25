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
        if (!isset($data['functionName']) || !isset($data['params'])) {
            throw new \Exception("Bad formated data Structure");
        }
        $data = $this->getArgumentsStructure($data['functionName'], $data['params']);

        return $this->rawEncode($data, $type);
    }

    protected function rawEncode(array $data, $type)
    {
        if (!isset($data[0]) || !isset($data[1])){
            throw new \Exception("Bad formated argument and parameters");
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

    public function getArgumentsStructure($functionName, array $params)
    {
        $validFunctionNames = array('insert', 'lookup', 'delete', 'info');
        if (!in_array($functionName, $validFunctionNames)) {
            throw new \Exception('Invalid method!');
        }
        return $this->$functionName($params);
    }

    protected function insert(array $params)
    {
         return array("[~a, {~a, ~s}]", $params);
    }

    protected function lookup(array $params)
    {
        return array("[~a, ~a]", $params);
    }

    protected function delete(array $params)
    {
        return array("[~a, ~a]", $params);
    }

    protected function info(array $params)
    {
        return array("[~a]", $params);
    }

}
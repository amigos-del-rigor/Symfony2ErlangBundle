<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Encoder;

Class PebFormatter
{
    /**
     * Format array structure to acomplish
     * peb method specifications
     *
     * @param string $functionName Ets Method
     * @param  array  $params
     * @return array (format Structure, parameters)
     */
    public function getArgumentsStructure($functionName, array $params)
    {
        $validFunctionNames = array('insert', 'lookup', 'delete', 'info', 'get_pid_from_id', 'set', 'get', 'setPidList');
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

    protected function get_pid_from_id(array $params)
    {
        return array("[~s]", $params);
    }

    protected function set(array $params)
    {
        return array("[~P, ~s, ~s]", $params);
    }

    protected function get(array $params)
    {
        return array("[~P, ~s]", $params);
    }

    protected function setPidList(array $params)
    {
        return array("[~s, ~s, ~s]", $params);
    }

}
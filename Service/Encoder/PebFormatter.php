<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Service\Encoder;

Class PebFormatter
{
    const insert            = "[~a, {~a, ~s}]";
    const lookup            = "[~a, ~a]";
    const delete            = "[~a, ~a]";
    const info              = "[~a]";
    const get_pid_from_id   = "[~s]";
    const set               = "[~P, ~s, ~s]";
    const get               = "[~P, ~s]";
    const setPidList        = "[~s, ~s, ~s]";

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
        return array(constant(sprintf('self::%s', $functionName)), $params);
    }

    protected function getDefinedConstants()
    {
        $reflClass = new \ReflectionObject($this);

        return array_keys($reflClass->getConstants());
    }
}
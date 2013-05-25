<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Encoder;

use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\PebFormatter;

class PebFormatterTest extends \PHPUnit_Framework_TestCase
{
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new PebFormatter();
    }
    /**
     * @group okis
     * @dataProvider getArguments
     */
    public function testGetArgumentsStructure($type, $result)
    {
        $data = $this->formatter->getArgumentsStructure($type, array());
        $this->assertEquals($data, array($result, array()));
    }

    public function getArguments()
    {
        return array(
            array('insert', '[~a, {~a, ~s}]'),
            array('lookup', '[~a, ~a]'),
            array('info', '[~a]'),
            array('delete', '[~a, ~a]'),
        );
    }
}
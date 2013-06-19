<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Plugin;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\Rest;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\JsonEncoder;
use Mockery;

class RestTest extends \PHPUnit_Framework_TestCase
{
    protected $process;
    protected $rest;

    public function setUp()
    {
        $this->rest = new Rest($this->getFakeJsonEncoder(''));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Bad Method Resource Structure
     */
    public function testCallTrhowExceptionWhenMethodIsBadFormatted()
    {
        $this->rest->call('badFormat');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage callable fakemethod not found
     */
    public function testCallTrhowExceptionWhenNoCallableMethod()
    {
        $this->rest->call(array('method' => 'fakeMethod'));
    }

    public function getFakeJsonEncoder($encodedData)
    {
        $mock = \Mockery::mock('ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\EncoderInterface');
        $mock->shouldReceive('encode')
            ->andReturn($encodedData);

        return $mock;
    }
}
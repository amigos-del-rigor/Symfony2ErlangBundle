<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Encoder;

use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\PebEncoder;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\PebFormatter;

class PebTest extends \PHPUnit_Framework_TestCase
{
    protected function getFakePebETSFormatter($params)
    {
        $mock = \Mockery::mock('ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\PebFormatter');
        $mock->shouldReceive('getArgumentsStructure')
            ->andReturn(array("[~a, ~a]", $params));

        return $mock;
    }


    public function testVEncodeAndDecodeArray()
    {
        $pebFormatter = $this->getFakePebETSFormatter(array());
        $encoder = new PebEncoder($pebFormatter);

        $r = new \ReflectionObject($encoder);
        $m = $r->getMethod('rawEncode');
        $m->setAccessible(true);

        $data = array("[~s]" , 'message');
        $encodeData = $m->invoke($encoder, $data, 'vencode');
        $decodedData = $encoder->decode($encodeData, 'vencode');

        $this->assertTrue(is_array($decodedData));
        $this->assertEquals($data[1], $decodedData[0]);
    }

    public function testEncodeAndDecodeArray()
    {
        $pebFormatter = $this->getFakePebETSFormatter(array());
        $encoder = new PebEncoder($pebFormatter);
        $r = new \ReflectionObject($encoder);
        $m = $r->getMethod('rawEncode');
        $m->setAccessible(true);
        $data = array("[~s]" , 'message');
        $encodeData = $m->invoke($encoder, $data, 'encode');

        $decodedData = $encoder->decode($encodeData);

        $this->assertTrue(is_array($decodedData));
        $this->assertEquals($data[1], $decodedData[0]);
    }

    /**
     *
     * @expectedException Exception
     * @expectedExceptionMessage Bad formated argument and parameters
     */
    public function testFailRawEncodeBadParameters()
    {
        $pebFormatter = $this->getFakePebETSFormatter(array());
        $encoder = new PebEncoder($pebFormatter);
        $r = new \ReflectionObject($encoder);
        $m = $r->getMethod('rawEncode');
        $m->setAccessible(true);
        $encodeData = $m->invoke($encoder, array(), 'encode');
    }

    /**
     *
     * @expectedException Exception
     * @expectedExceptionMessage Bad formated data Structure
     */
    public function testFailEncodeBadParameters()
    {
        $pebFormatter = $this->getFakePebETSFormatter(array());
        $encoder = new PebEncoder($pebFormatter);
        $outputChannel = $encoder->encode(array());
    }

    /**
     *
     * @group test
     */
    public function testRawEncodeAndDecodeMultiData()
    {
        $params = array('test', 'message');
        $data = array("[~a,~a]", $params);
        $pebFormatter = $this->getFakePebETSFormatter($data);
        $encoder = new PebEncoder($pebFormatter);
        $r = new \ReflectionObject($encoder);
        $m = $r->getMethod('rawEncode');
        $m->setAccessible(true);
        $encodeData = $m->invoke($encoder, $data, 'encode');
        $decodedData = $encoder->decode($encodeData);

        $this->assertTrue(is_array($decodedData));
        $this->assertEquals($params[0], $decodedData[0]);
        $this->assertEquals($params[1], $decodedData[1]);
    }

    /**
     * Implemented as Integration test
     * Needs to handle Real PebFormatter
     * to check result
     */
    public function testEncodeAndDecodeMultiData()
    {
        $pebFormatter = new PebFormatter();
        $encoder = new PebEncoder($pebFormatter);
        $params = array('test', 'message');
        $data = array("[~a,~a]", $params);

        $data = array(
            'functionName' => 'lookup',
            'params' => $params
        );

        $encodeData = $encoder->encode($data);
        $decodedData = $encoder->decode($encodeData);
        $this->assertTrue(is_array($decodedData));
        $this->assertEquals($params[0], $decodedData[0]);
        $this->assertEquals($params[1], $decodedData[1]);
    }
}
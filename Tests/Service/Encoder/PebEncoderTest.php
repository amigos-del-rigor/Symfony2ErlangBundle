<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Encoder;

use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\PebEncoder;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\PebFormatter;

class PebEncoderTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        if (!extension_loaded('peb')) {
            $this->markTestSkipped('You need the PHP Erlang Bridge extension to run these tests');
        }
    }

    public function testVEncodeAndDecodeArray()
    {
        $encoder = new PebEncoder();

        $r = new \ReflectionObject($encoder);
        $m = $r->getMethod('rawEncode');
        $m->setAccessible(true);

        $data = array('[~s]' , 'message');
        $encodeData = $m->invoke($encoder, $data, 'vencode');
        $decodedData = $encoder->decode($encodeData, 'vencode');

        $this->assertTrue(is_array($decodedData));
        $this->assertEquals($data[1], $decodedData[0]);
    }

    public function testEncodeAndDecodeArray()
    {
        $encoder = new PebEncoder();
        $r = new \ReflectionObject($encoder);
        $m = $r->getMethod('rawEncode');
        $m->setAccessible(true);
        $data = array('[~s]' , 'message');
        $encodeData = $m->invoke($encoder, $data, 'encode');

        $decodedData = $encoder->decode($encodeData);

        $this->assertTrue(is_array($decodedData));
        $this->assertEquals($data[1], $decodedData[0]);
    }

    /**
     *
     * @expectedException Exception
     * @expectedExceptionMessage Bad formatted argument and parameters
     */
    public function testFailRawEncodeBadParameters()
    {
        $encoder = new PebEncoder();
        $r = new \ReflectionObject($encoder);
        $m = $r->getMethod('rawEncode');
        $m->setAccessible(true);
        $encodeData = $m->invoke($encoder, array(), 'encode');
    }

    /**
     *
     * @expectedException Exception
     * @expectedExceptionMessage Bad formatted data Structure
     */
    public function testFailEncodeBadParameters()
    {
        $encoder = new PebEncoder();
        $outputChannel = $encoder->encode(array());
    }

    /**
     *
     * @group test
     */
    public function testRawEncodeAndDecodeMultiData()
    {
        $params = array('test', 'message');
        $data = array('[~a,~a]', $params);
        $encoder = new PebEncoder();
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
        $encoder = new PebEncoder();
        $params = array('[~a,~a]',array('test', 'message'));
        $data = array('[~a,~a]', $params);

        $data = array(
            'functionName' => 'lookup',
            'params' => $params[1],
            'structure' => $params[0]
        );

        $encodeData = $encoder->encode($data);
        $decodedData = $encoder->decode($encodeData);
        $this->assertTrue(is_array($decodedData));
        $this->assertEquals($params[1][0], $decodedData[0]);
        $this->assertEquals($params[1][1], $decodedData[1]);
    }
}
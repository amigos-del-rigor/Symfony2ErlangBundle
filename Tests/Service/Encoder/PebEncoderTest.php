<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Encoder;

use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\PebEncoder;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\PebFormatter;

class PebTest extends \PHPUnit_Framework_TestCase
{
    protected $encoder;

    public function setUp()
    {
        $pebFormatter = new PebFormatter();
        $this->encoder = new PebEncoder($pebFormatter);
    }


    public function testVEncodeAndDecodeArray()
    {
        $r = new \ReflectionObject($this->encoder);
        $m = $r->getMethod('rawEncode');
        $m->setAccessible(true);


        $data = array("[~s]" , 'message');
        $encodeData = $m->invoke($this->encoder, $data, 'vencode');
        $decodedData = $this->encoder->decode($encodeData, 'vencode');

        $this->assertTrue(is_array($decodedData));
        $this->assertEquals($data[1], $decodedData[0]);
    }

    public function testEncodeAndDecodeArray()
    {
        $r = new \ReflectionObject($this->encoder);
        $m = $r->getMethod('rawEncode');
        $m->setAccessible(true);
        $data = array("[~s]" , 'message');
        $encodeData = $m->invoke($this->encoder, $data, 'encode');

        $decodedData = $this->encoder->decode($encodeData);

        $this->assertTrue(is_array($decodedData));
        $this->assertEquals($data[1], $decodedData[0]);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Bad formated argument and parameters
     */
    public function testFailRawEncodeBadParameters()
    {
        $r = new \ReflectionObject($this->encoder);
        $m = $r->getMethod('rawEncode');
        $m->setAccessible(true);
        $encodeData = $m->invoke($this->encoder, array(), 'encode');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Bad formated data Structure
     */
    public function testFailEncodeBadParameters()
    {
        $outputChannel = $this->encoder->encode(array());
    }

    public function testRawEncodeAndDecodeMultiData()
    {
        $params = array('test', 'message');
        $data = array("[~a,~a]", $params);
        $r = new \ReflectionObject($this->encoder);
        $m = $r->getMethod('rawEncode');
        $m->setAccessible(true);
        $encodeData = $m->invoke($this->encoder, $data, 'encode');
        $decodedData = $this->encoder->decode($encodeData);

        $this->assertTrue(is_array($decodedData));
        $this->assertEquals($params[0], $decodedData[0]);
        $this->assertEquals($params[1], $decodedData[1]);
    }

    /**
     *
     * @group testoki
     */
    public function testEncodeAndDecodeMultiData()
    {
        $params = array('test', 'message');
        $data = array("[~a,~a]", $params);

        $data = array(
            'functionName' => 'lookup',
            'params' => $params
        );

        $encodeData = $this->encoder->encode($data);
        $decodedData = $this->encoder->decode($encodeData);
        $this->assertTrue(is_array($decodedData));
        $this->assertEquals($params[0], $decodedData[0]);
        $this->assertEquals($params[1], $decodedData[1]);
    }
}
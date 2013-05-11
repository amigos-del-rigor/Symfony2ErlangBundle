<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Encoder;

use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\PebEncoder;

class PebTest extends \PHPUnit_Framework_TestCase
{
    protected $encoder;

    public function setUp()
    {
        $this->encoder = new PebEncoder();
    }

    public function testVEncodeAndDecodeArray()
    {
        $data = array("[~s]" , 'message');
        $encodeData = $this->encoder->encode($data, 'vencode');
        $decodedData = $this->encoder->decode($encodeData, 'vencode');

        $this->assertTrue(is_array($decodedData));
        $this->assertEquals($data[1], $decodedData[0]);
    }

    public function testEncodeAndDecodeArray()
    {
        $data = array("[~s]" , 'message');
        $encodeData = $this->encoder->encode($data);
        $decodedData = $this->encoder->decode($encodeData);

        $this->assertTrue(is_array($decodedData));
        $this->assertEquals($data[1], $decodedData[0]);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Bad formated data
     */
    public function testFailEncodeBadParameters()
    {
        $outputChannel = $this->encoder->encode(array());
    }

    public function testEncodeAndDecodeMultiData()
    {
        $params = array('test', 'message');
        $data = array("[~a,~a]", $params);
        $encodeData = $this->encoder->encode($data);
        $decodedData = $this->encoder->decode($encodeData);

        $this->assertTrue(is_array($decodedData));
        $this->assertEquals($params[0], $decodedData[0]);
        $this->assertEquals($params[1], $decodedData[1]);
    }

    //@TODO: HAS TO DOO WIHT PEB_RPC FAILURE
    // response Violacion de segmento
    //
    // public function testEncodeAndDecodeTupleData()
    // {
    //     $params = array('test', 'message');
    //     $data = array('keys' => "[~a, {~a, ~s}]", 'params' => $params);
    //     $encodeData = $this->encoder->encode($data);
    //     $decodedData = $this->encoder->decode($encodeData);
    // }
}
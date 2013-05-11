<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Encoder;

use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\JsonEncoder;

class JsonTest extends \PHPUnit_Framework_TestCase
{
    protected $encoder;

    public function setUp()
    {
        $this->encoder = new JsonEncoder();
    }

    public function testEncodeAndDecodeArray()
    {
        $data = array('key' => 'value');
        $encodeData = $this->encoder->encode($data);
        $decodedData = $this->encoder->decode($encodeData);

        $this->assertEquals($data, $decodedData);
        $this->assertEquals($data['key'], $decodedData['key']);
    }

    public function testEncodeAndDecodeStdClass()
    {
        $data = array('key' => 'value');
        $encodeData = $this->encoder->encode($data, null);
        $decodedData = $this->encoder->decode($encodeData, 'obj');

        $this->assertEquals($data['key'], $decodedData->key);
    }
}
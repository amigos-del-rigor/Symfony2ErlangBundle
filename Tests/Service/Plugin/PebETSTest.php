<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Plugin;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\Peb;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\PebEncoder;

class PebETSTest extends \PHPUnit_Framework_TestCase {

    protected $peb;

    public function setUp()
    {
        $encoder = new PebEncoder();
        $this->peb = new Peb($encoder);
        $this->peb->setNode('node0@127.0.0.1');
        $this->peb->setCookie('abc');
        $this->peb->setTimeout(2);
    }

    public function testInsertETSCall()
    {
        $result = $this->peb->call('ets', 'insert', array('test',array('key9', 'value3')));
        $this->assertEquals($result, 'true');
    }

    public function testFetchETSCall()
    {
        $result = $this->peb->call('ets', 'lookup', array('test', 'key9'));

        $this->assertTrue(is_array($result));
        $this->assertEquals($result[0][0], 'key9');
        $this->assertEquals($result[0][1], 'value3');
    }
}

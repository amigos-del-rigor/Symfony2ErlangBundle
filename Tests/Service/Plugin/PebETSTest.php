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
        $this->peb->setTimeout(200);
       //$this->addFixture();
    }

    public function testInsertETSCall()
    {
        //ets:insert(T,{key, value})
        $result = $this->peb->call('ets', 'insert', array('test',array('key9', 'value3')));
        $this->assertEquals($result, 'true');
    }

    public function testFetchETSCall()
    {
        // ets:lookup(test, key9).
        $result = $this->peb->call('ets', 'lookup', array('test', 'key9'));

        $this->assertTrue(is_array($result));
        $this->assertEquals($result[0][0], 'key9');
        $this->assertEquals($result[0][1], 'value3');
    }

    public function testInfoETSCall()
    {
        // ets:lookup(test, key9).
        $result = $this->peb->call('ets', 'info', array('test'));
        // var_dump($result);
        $this->assertTrue(is_array($result));
        $this->assertEquals($result[4][1], 'test');
        $this->assertEquals($result[5][1], 1);
        $this->assertEquals($result[7][1], 'true');
    }

    /**
     * @group test
     */
    public function testAddFixture()
    {
        //ets:new(test, [set, named_table, public]).
        $result = $this->peb->call(
            'ets', 'new', array('teste', array('set', 'named_table', 'public'))
        );
        var_dump($result);
        $this->assertEquals($result, 'teste');
        $result = $this->peb->call('ets', 'info', array('teste'));
        var_dump($result);
    }


    public function tearDown()
    {
        //ets:delete(table).
         // $result = $this->peb->call('ets', 'delete', array('test'));
         // var_dump($result);
        //$this->assertEquals($result, 'true');

    }
}

<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Service\Plugin;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\Peb;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\PebEncoder;
use Symfony\Component\Process\Process;

class PebETSTest extends \PHPUnit_Framework_TestCase
{
    protected $process;
    protected $peb;

    /**
     * Remember to initiate Erlang Node
     *     erl -sname node0 -setcookie abc
     *     ets:new(test, [set, named_table, public]).
     */
    public function setUp()
    {
        $encoder = new PebEncoder();
        $this->peb = new Peb($encoder);
        $this->peb->setNode('node0@127.0.0.1');
        $this->peb->setCookie('abc');
        $this->peb->setTimeout(200);
    }

    public function testInsertETSCall()
    {
        //ets:insert(T,{key, value})
        $result = $this->peb->call('ets', 'insert', array('test',array('key', 'value')));
        $this->assertEquals($result, 'true');
    }

    public function testFetchETSCall()
    {
        // ets:lookup(test, key9).
        $result = $this->peb->call('ets', 'lookup', array('test', 'key'));

        $this->assertTrue(is_array($result));
        $this->assertEquals($result[0][0], 'key');
        $this->assertEquals($result[0][1], 'value');
    }

    public function testInfoETSCall()
    {
        // ets:lookup(test, key9).
        $result = $this->peb->call('ets', 'info', array('test'));
        $this->assertTrue(is_array($result));
        $this->assertEquals($result[4][1], 'test');
        $this->assertEquals($result[5][1], 1);
        $this->assertEquals($result[7][1], 'true');
    }

    public function testDeleteItemCall()
    {
        $result = $this->peb->call('ets', 'delete', array('test', 'key'));
        $this->assertEquals('true', $result);
    }

    /**
     * @large
     */
    public function testInsertAndFetchOnLongQueue()
    {
        $items = 1000;
        $key= uniqid();
        for ($i=0; $i < $items ; $i++) {
            $result = $this->peb->call('ets', 'insert', array('test',array($key.$i, 'value'.$i)));
        $result = $this->peb->call('ets', 'lookup', array('test', $key.$i));

        $this->assertTrue(is_array($result));
        $this->assertEquals($result[0][0], $key.$i);
        $this->assertEquals($result[0][1], 'value'.$i);
        }

        $totalItems = $this->peb->call('ets', 'info', array('test'));
        $this->assertTrue(is_array($totalItems));
        $this->assertArrayHasKey(5, $totalItems);
        $this->assertEquals($totalItems[5][1], $items);

        //delete items
        for ($i=0; $i < $items; $i++) {
            $result = $this->peb->call('ets', 'delete', array('test', $key.$i));
            $this->assertEquals('true', $result);
        }

    }

    public function tearDown()
    {
        $this->peb = null;
    }
}

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
     * @group node
     * @return [type] [description]
     */
    public function testCreateCNode()
    {
        //ets:new(test, [set, named_table, public]).
        $result = $this->peb->call(
            'ets', 'new', array('testing', array('set', 'named_table', 'public'))
        );
        var_dump($result);
        $this->assertEquals($result, 'testing');
        $result = $this->peb->call('ets', 'info', array('testing'));
        var_dump($result);
    }

    public function setUp()
    {
        $this->startCNode();
        $encoder = new PebEncoder();
        $this->peb = new Peb($encoder);
        $this->peb->setNode('node1@127.0.0.1');
        $this->peb->setCookie('abc');
        $this->peb->setTimeout(2);
       //$this->addFixture();
    }

    protected function launchCnode()
    {
        $command = 'erl -sname node1 -setcookie abc';
        $this->process = new Process($command);
        $this->process->start();
    }

    public function startCNode()
    {
        if ($this->process) {
            echo "process Running";
            $this->process->stop();
        }

        // $command = 'erl -sname node1 -setcookie abc';
        // $this->process = new Process($command);
        // $this->process->start();
        $this->launchCnode();
        for ($i=0; $i < 1000000; $i++) {
            if ($this->process->isRunning() === false) {
                $this->launchCnode();
                echo "launching NODE";
            }
            var_dump($this->process->isRunning());
            //sleep(1);
        }
        // sleep(1);
        if (!$this->process->isRunning()) {
            $this->fail('Could not start C Node');
        }
        die();
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
        // //ets:delete(table).
        // $result = $this->peb->call('ets', 'delete', array('testing'));
        // var_dump($result);
        // $this->assertEquals($result, 'true');
        // $result = $this->peb->closeChannel();
        // var_dump($result);
        // if ($this->process) {
        //     $this->process->stop();
        // }
    }
}

<?php
/**
 * @author Jordi Llonch <llonch.jordi@gmail.com>
 * @date 27/04/13 13:24
 */

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Plugins;

use ADR\Bundle\Symfony2ErlangBundle\Service\Plugin\RpcAmqp;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\JsonEncoder;

class RpcAmqpTest extends \PHPUnit_Framework_TestCase {

    protected $rpcAmqp;

    public function setUp()
    {
        $encoder = new JsonEncoder();
        $this->rpcAmqp = new RpcAmqp($encoder);
        $this->rpcAmqp->setHost('localhost');
        $this->rpcAmqp->setPort(5672);
        $this->rpcAmqp->setUser('guest');
        $this->rpcAmqp->setPassword('guest');
    }

    public function testCall()
    {
        $result = $this->rpcAmqp->call('mymodule', 'sum', array(30));
        $this->assertEquals($result, 31);
    }
}

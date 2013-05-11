<?php
/**
 * @author Jordi Llonch <llonch.jordi@gmail.com>
 * @date 27/04/13 13:24
 */

namespace ADR\Bundle\Symfony2ErlangBundle\Tests\Plugins;

use ADR\Bundle\Symfony2ErlangBundle\Lib\Plugins\RPCAMQP;

class RPCAMQPTest extends \PHPUnit_Framework_TestCase {

    public function testCall()
    {
        // $channelsParams = array(
        //     'rpc_amqp_node0' => array(
        //         'host' => 'localhost',
        //         'port' => 5672,
        //         'user' => 'guest',
        //         'password' => 'guest'
        // ));
        // $plugin = new RPCAMQP($channelsParams);
        // $plugin->setChannel('rpc_amqp_node0');
        // $plugin->getChannel();
        // $result = $plugin->call('mymodule', 'sum', 30);
        // $this->assertEquals(31, $result);
    }
}

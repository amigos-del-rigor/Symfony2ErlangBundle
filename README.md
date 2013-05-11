Symfony2ErlangBundle
====================

Test
----
The test suit checks if all plugins are working and so it is necessary to set some things in the Erlang part.

    # PEB
    erl -sname node0 -setcookie abc
    ets:new(test, [set, named_table, public]).

    # RPC-AMQP
    Start RabbitMQ
    cd Erlang/RPC_AMQP
    ./rpc_server.erl

Start test:

    # test
    phpunit


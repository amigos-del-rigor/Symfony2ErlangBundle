Symfony2ErlangBundle
====================

Quick start
-----------
    Add Bundle on composer
        "adr/symfony2erlang-bundle":"dev-master"

    On config.yml:

        adr_symfony2_erlang:
            channels: %adr_symfony2_erlang.channels%

    configure your channels on parameters.yml

    you can define all channels that you need
    just remember to use the same type as service tag channel

    composer update

Channel Definition Parameters:
------------------------------
    peb_node0:
        type:   peb
        config:
            node: 'node0@machine'
            cookie: 'abc'
            timeout: 2
    peb_node1:
        type:   peb
        config:
            node: 'node0@127.0.0.1'
            cookie: 'fh38ga00SIUG'
            timeout: 2
    rest_node0:
        type:   rest
        config:
            host: 'http://localhost'
            port: 80
    rpc_amqp_node0:
        type:   rpc_amqp
        configs:
            host: 'localhost'
            port: 5672
            user: 'guest'
            password: 'guest'
    socket_node0:
        type:   socket
        configs:
            host: 'localhost'
            port: 8080

Start test:
-----------
    # PEB
    erl -sname node0 -setcookie abc
    ets:new(test, [set, named_table, public]).
    # Peb demo on /demo/hello/test

    # RPC-AMQP
    Start RabbitMQ
    cd Erlang/RpcAmqp
    make run

    # test
    phpunit


Configuring API REST Handler:
-----------------------------
    By default noop handler is set as rest handler alias , if you want to
    override it just declare on your config.yml:

    adr_symfony2_erlang:
        services:
            api.rest.handler: adr_symfony2_erlang.api.rest.handler.noop

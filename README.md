Symfony2ErlangBundle
====================

Quick start
-----------
    Add Bundle on composer
        "adr/symfony2erlang-bundle":"dev-master"

    cp app/config/parameters.yml.sample app/config/parameters.yml

    On config.yml:

        adr_symfony2_erlang:
            environment: %adr_symfony2_erlang.environment%
            channels: %adr_symfony2_erlang.channels%

    configure your channels on parameters.yml

    yo can define all channels that you need
    just remember to use the same type as service tag channel

    composer update

    # PEB
    erl -sname node0 -setcookie abc
    ets:new(test, [set, named_table, public]).
    # Peb demo on /demo/hello/test

    # RPC-AMQP
    Start RabbitMQ
    cd Erlang/RPC_AMQP
    ./rpc_server.erl

Start test:

    # test
    phpunit


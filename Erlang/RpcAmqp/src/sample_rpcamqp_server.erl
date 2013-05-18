-module(sample_rpcamqp_server).

%% API
-export([run/0]).

-include_lib("amqp_client/include/amqp_client.hrl").

run() ->
  {ok, Connection} = amqp_connection:start(#amqp_params_network{host = "localhost"}),
  lager:debug(" [*] Waiting for messages. To exit press CTRL+C"),

  RpcHandler = fun(X) ->
    lager:debug("X: ~p", [X]),
    Decoded = jsx:decode(X),
    lager:debug("Decoded: ~p", [Decoded]),
    Result = lists:sum(Decoded),
    lager:debug("Result: ~p", [Result]),
    jsx:encode(Result)
  end,
  _Server = amqp_rpc_server:start(Connection, <<"mymodule:sum">>, RpcHandler),

  receive _Msg -> ok
  end.


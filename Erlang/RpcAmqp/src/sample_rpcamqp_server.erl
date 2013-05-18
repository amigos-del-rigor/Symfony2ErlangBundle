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
% X is json: [Number]
%%     ListData = binary_to_list(X),
%%     Decoded = string:substr(ListData, 2, string:len(ListData)-2),
%%     Value = list_to_integer(Decoded),
%%     lager:debug(" -> ~p", [Value]),
%%     Result = Value + 1,
%%     integer_to_binary(Result)
    jsx:encode(Result)
  end,
  _Server = amqp_rpc_server:start(Connection, <<"mymodule:sum">>, RpcHandler),

  receive _Msg -> ok
  end.


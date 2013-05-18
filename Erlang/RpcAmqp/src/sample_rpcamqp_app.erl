-module(sample_rpcamqp_app).

-behaviour(application).

%% Application callbacks
-export([start/2, stop/1]).

%% ===================================================================
%% Application callbacks
%% ===================================================================

start(_StartType, _StartArgs) ->
    lager:set_loglevel(lager_console_backend, debug),
    sample_rpcamqp_sup:start_link().

stop(_State) ->
    ok.

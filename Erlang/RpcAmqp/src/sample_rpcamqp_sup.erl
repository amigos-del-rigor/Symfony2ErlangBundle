
-module(sample_rpcamqp_sup).

-behaviour(supervisor).

%% API
-export([start_link/0]).

%% Supervisor callbacks
-export([init/1]).

%% Helper macro for declaring children of supervisor
-define(CHILD(I, Type), {I, {I, start_link, []}, permanent, 5000, Type, [I]}).

%% ===================================================================
%% API functions
%% ===================================================================

start_link() ->
    supervisor:start_link({local, ?MODULE}, ?MODULE, []).

%% ===================================================================
%% Supervisor callbacks
%% ===================================================================

init([]) ->
  RpcAmqpChildSpec = {{local, sample_rpcamqp_server},
    {sample_rpcamqp_server, run, []},
    permanent, infinity, worker, [hs_account_service]},
  {ok, {{one_for_one, 5, 10}, [
    RpcAmqpChildSpec
  ]}}.

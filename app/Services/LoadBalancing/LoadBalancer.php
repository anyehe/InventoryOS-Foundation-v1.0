<?php
namespace App\Services\LoadBalancing;
class LoadBalancer { public function choose(array $servers, ?string $algorithm=null): ?array { $algorithm=$algorithm ?: config('api.load_balancer.algorithm'); $strategy=match($algorithm){ 'weighted_round_robin'=>new WeightedRoundRobinStrategy(), 'least_connections'=>new LeastConnectionsStrategy(), default=>new RoundRobinStrategy() }; return $strategy->choose($servers); } }

<?php
namespace App\Services\LoadBalancing;
class RoundRobinStrategy implements LoadBalancerStrategy { private int $cursor=0; public function choose(array $servers): ?array { if(!$servers) return null; $server=$servers[$this->cursor % count($servers)]; $this->cursor++; return $server; } }

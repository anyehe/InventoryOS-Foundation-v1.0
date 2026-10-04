<?php
namespace App\Services\LoadBalancing;
class LeastConnectionsStrategy implements LoadBalancerStrategy { public function choose(array $servers): ?array { if(!$servers) return null; usort($servers, fn($a,$b)=>(int)($a['connections']??0)<=>(int)($b['connections']??0)); return $servers[0]; } }

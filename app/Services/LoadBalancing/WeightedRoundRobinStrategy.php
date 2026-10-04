<?php
namespace App\Services\LoadBalancing;
class WeightedRoundRobinStrategy implements LoadBalancerStrategy { public function choose(array $servers): ?array { $pool=[]; foreach($servers as $s){ for($i=0;$i<max(1,(int)($s['weight']??1));$i++) $pool[]=$s; } return $pool ? $pool[array_rand($pool)] : null; } }

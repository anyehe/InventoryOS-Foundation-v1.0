<?php
namespace App\Services\LoadBalancing;
interface LoadBalancerStrategy { public function choose(array $servers): ?array; }

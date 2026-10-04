<?php
namespace App\Http\Controllers\Api\V1;
use Illuminate\Support\Facades\DB;
class HealthController extends ApiController { public function __invoke(){ $db='ok'; try{ DB::select('select 1'); } catch(\Throwable $e){ $db='failed'; } $ok=$db==='ok'; return response()->json(['status'=>$ok?'ok':'degraded','version'=>config('api.version'),'database'=>$db,'timestamp'=>now()->toIso8601String()],$ok?200:503); } }

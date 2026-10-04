<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
class ApiKeyController extends Controller {
    public function __construct(private AuditLogger $audit) {}
    public function index(){ return view('admin.api-keys.index',['keys'=>ApiKey::with('user')->latest()->paginate(15)]); }
    public function store(Request $request){
        $data=$request->validate(['name'=>['required','string','max:100'],'expires_at'=>['nullable','date','after:today'],'abilities'=>['nullable','array'],'abilities.*'=>['string','max:60']]);
        $allowed=['products:read','inventory:read','sales:read','purchases:read','reports:read'];
        $abilities=array_values(array_intersect($data['abilities'] ?? config('api.default_key_abilities'), $allowed));
        [$key,$plain]=ApiKey::issue($request->user()->id,$data['name'],$abilities,isset($data['expires_at'])?new \DateTime($data['expires_at']):null);
        $this->audit->record($request,'API_KEY_CREATED','success',['api_key_id'=>$key->id,'prefix'=>$key->key_prefix]);
        return back()->with('new_api_key',$plain)->with('status','API key created. Copy it now; the full key will not be shown again.');
    }
    public function revoke(Request $request, ApiKey $apiKey){
        $apiKey->update(['revoked_at'=>now()]); $this->audit->record($request,'API_KEY_REVOKED','success',['api_key_id'=>$apiKey->id]);
        return back()->with('status','API key revoked.');
    }
}

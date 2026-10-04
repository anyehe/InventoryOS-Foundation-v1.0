<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
class UserController extends Controller {
    public function __construct(private AuditLogger $audit) {}
    public function index(){ return view('admin.users.index',['users'=>User::with('roleRelation')->latest()->paginate(15),'roles'=>Role::orderBy('name')->get()]); }
    public function store(Request $request){
        $data=$request->validate(['name'=>['required','string','max:120'],'email'=>['required','email','max:255','unique:users,email'],'password'=>['required','string','max:72',Password::min(config('security.password.min_length'))->mixedCase()->numbers()->symbols()],'role_id'=>['required','exists:roles,id']]);
        $user=User::create([...$data,'password'=>Hash::make($data['password']),'role'=>Role::findOrFail($data['role_id'])->name]);
        $this->audit->record($request,'USER_CREATED','success',['target_user_id'=>$user->id]);
        return back()->with('status','User created.');
    }
    public function updateRole(Request $request, User $user){
        abort_if($user->id===$request->user()->id,422,'Use a separate administrator account to change your own role.');
        $data=$request->validate(['role_id'=>['required',Rule::exists('roles','id')]]); $role=Role::findOrFail($data['role_id']);
        $user->update(['role_id'=>$role->id,'role'=>$role->name]); $this->audit->record($request,'USER_ROLE_CHANGED','success',['target_user_id'=>$user->id,'role'=>$role->name]);
        return back()->with('status','Role updated.');
    }
}

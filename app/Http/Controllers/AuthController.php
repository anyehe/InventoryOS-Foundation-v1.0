<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\AuditLog;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller {
    public function showLogin() { return view('auth.login'); }
    public function login(Request $request) {
        $credentials = $request->validate(['email'=>['required','email','max:255'],'password'=>['required','string','max:72']]);
        $key = 'login:'.strtolower($credentials['email']).'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            AuditLog::create(['action'=>'LOGIN_RATE_LIMITED','ip_address'=>$request->ip(),'request_id'=>$request->header('X-Request-ID'),'result'=>'blocked','metadata'=>['email'=>hash('sha256',strtolower($credentials['email']))]]);
            throw ValidationException::withMessages(['email'=>'Too many login attempts. Please try again later.']);
        }
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($key, 60);
            AuditLog::create(['action'=>'LOGIN_FAILURE','ip_address'=>$request->ip(),'request_id'=>$request->header('X-Request-ID'),'result'=>'failure','metadata'=>['email'=>hash('sha256',strtolower($credentials['email']))]]);
            throw ValidationException::withMessages(['email'=>'The provided credentials are invalid.']);
        }
        RateLimiter::clear($key);
        $request->session()->regenerate();
        AuditLog::create(['user_id'=>Auth::id(),'action'=>'LOGIN_SUCCESS','ip_address'=>$request->ip(),'request_id'=>$request->header('X-Request-ID'),'result'=>'success']);
        return redirect()->intended(route('dashboard'));
    }
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

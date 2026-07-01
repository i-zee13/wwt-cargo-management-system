<?php

namespace App\Http\Controllers\Auth;

use Cookie;
use Illuminate\Support\Str;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DB;
use Illuminate\Http\JsonResponse;
use Hash;
use App\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Validation\ValidationException;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
     
        
        public function showLoginForm()
        {
            // Logout from 'clients' guard if authenticated
            if (Auth::guard('clients')->check()) {
                Auth::guard('clients')->logout();
            }
         
            if (Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
            } 
            Session::flush();
            Session::regenerate(); 
            Cookie::queue(Cookie::forget(Auth::guard('clients')->getRecallerName()));
            Cookie::queue(Cookie::forget(Auth::guard('web')->getRecallerName())); 
            return view('auth.login')->with('csrf_token', csrf_token());
        }
        
    public function login(Request $request)
    {   
        $this->validateLogin($request);
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }  
        $user        = User::where('username', $request['username'])->first();
        if ($user && $user->active == 1) { 
            if ($this->attemptLogin($request)) {
                if ($request->hasSession()) {
                    $request->session()->put('auth.password_confirmed_at', time());
                }
                $this->logLoginAttempt($request);
                return $this->sendLoginResponse($request);
            }
        } else {
            return redirect()->back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'User is no longer active !']);
        } 
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }
    
    public function logLoginAttempt(Request $request)
    {
        $user                       =   FacadesAuth::user();
        $loginLog                   =   new LoginLog();
        if ($loginLog) {
            $loginLog->user_id      = $user->id;
            $loginLog->username     = $user->username;
            $loginLog->login_time   =  Carbon::now();
            $loginLog->ip_address   = $request->ip();
            $loginLog->mac_address  = substr(exec('getmac'), 0, 17);
            $loginLog->save();
        }
    }
    public function change_password()
    {
        return view('auth.change_password');
    }

    public function ChangeUserPassword(Request $request)
    { 
        $user_data = DB::table('users')->whereRaw('username = "' . $request->pass_username . '"')->first();
        if (Hash::check($request->old_pass, $user_data->password)) {
            if (Hash::check($request->new_password, $user_data->password)) {
                echo json_encode(101);
            } else {
                $update = DB::table('users')->whereRaw('username = "' . $request->pass_username . '"')->update([
                    'password' => bcrypt($request->new_password),
                    'password_changed' => 1
                ]);
                if ($update) {
                    echo json_encode(200);
                } else {
                    echo json_encode(202);
                }
            }
        } else {
            echo json_encode(201);
        }
    }


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    { 
        Auth::logout(); // Log out the user

        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate the CSRF token
       
        return redirect('/admin/login');
    }

    public function username()
    {
        return 'username';
    }
}

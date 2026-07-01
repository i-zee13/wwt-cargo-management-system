<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
 
    
    public function showResetForm(Request $request)
    {
           
        $token = $request->route()->parameter('token');
        $email = $request->email;  
        $tokenData = DB::table('password_resets')->where('email', $email)->first();  
        if (!$tokenData || !Hash::check($token, $tokenData->token)) {
            return redirect()->route('password.request')->withErrors(['email' => 'The password reset link has expired or is invalid.']);
        }
     
        if (Carbon::parse($tokenData->created_at)->addMinutes(config('auth.passwords.users.expire'))->isPast()) {
            return redirect()->route('password.request')->withErrors(['email' => 'The second else password reset link has expired or is invalid.']);
        }
    
 
        return view('auth.passwords.reset')->with(['token' => $token, 'email' => $email]);
    }
    protected function resetPassword($user, $password)
    {    
        $this->setUserPassword($user, $password); 
        $user->setRememberToken(Str::random(60)); 
        $user->last_name = "here there";
        $user->save(); 
        event(new PasswordReset($user)); 
        $this->guard()->login($user);
    }
    
}

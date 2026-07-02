<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use URL;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }
    public function sendResetLinkEmail(Request $request)
    {    
        $this->validateEmail($request);
        $user = User::where('email', $request['email'])->first();
        if (!$user) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'There is no account associated with this email address!']);
        } else if ($user->active == 0) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'The user is no longer available!']);
        } else {

            DB::table('password_resets')->where('email', $request->email)->delete();
            $response = $this->broker()->sendResetLink(
                $this->credentials($request)
            );
            $subject = emailContentSettings('reset')->subject ?? 'Password Reset Request';
            $headerContent = emailContentSettings('reset')->header_text ?? 'Hello, {{ first_name }}!';
            $bodyText = emailContentSettings('reset')->body_text ?? 'Click the link below to reset your password, {{ first_name }}.';
            $footerText = emailFooterText(emailContentSettings('reset')->footer_text ?? null);
            $loginUser = $user;
            $token = Password::broker()->createToken($user);
            $placeholders = [
                '{{ first_name }}' => $loginUser->first_name,
                '{{ email }}' => $loginUser->email,
            ];
            $headerContent = str_replace(array_keys($placeholders), array_values($placeholders), $headerContent);
            $bodyText = str_replace(array_keys($placeholders), array_values($placeholders), $bodyText);
            $footerText = str_replace(array_keys($placeholders), array_values($placeholders), $footerText);
            $resetUrl = URL::temporarySignedRoute(
                'password.reset',
                now()->addMinutes(config('auth.passwords.users.expire', 60)),
                ['token' => $token, 'email' => $loginUser->email]
            );
            $subject = "Reset Your Password";
            $htmlContent = view('auth.reset-password-temp', [
                'headerContent' => $headerContent,
                'bodyContent' => $bodyText,
                'footerContent' => $footerText,
                'resetUrl' => $resetUrl,
                'subject' => $subject,
            ])->render();
                 
            SendInBlue($loginUser->email, $loginUser->first_name, $subject, $htmlContent);
            return $response == Password::RESET_LINK_SENT
                ? $this->sendResetLinkResponse($request, $response)
                : $this->sendResetLinkFailedResponse($request, $response);
        }
    }
}

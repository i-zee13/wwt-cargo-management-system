<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ClientsModel;
use DB;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use URL;

class ClientForgotPasswordController extends Controller
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

    // use SendsPasswordResetEmails;

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
        return view('client_auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);
        $user = ClientsModel::where('email', $request['email'])->first();
        if (!$user) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'There is no account associated with this email address!']);
        }   else {

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
                'client.reset',
                now()->addMinutes(config('auth.passwords.clients.expire', 60)), // Use 'clients' broker expiration time
                ['token' => $token, 'email' => $loginUser->email]
            );
            
            $subject = "Reset Your Password";
            $htmlContent = view('client_auth.reset-password-temp', [
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

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    }

    /**
     * Get the needed authentication credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only('email');
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return $request->wantsJson()
                    ? new JsonResponse(['message' => trans($response)], 200)
                    : back()->with('status', trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        if ($request->wantsJson()) {
            throw ValidationException::withMessages([
                'email' => [trans($response)],
            ]);
        }

        return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => trans($response)]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('clients');
    }
}

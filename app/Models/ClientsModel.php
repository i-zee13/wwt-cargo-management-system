<?php

namespace App\Models;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Support\Facades\Auth;

class ClientsModel extends Model implements AuthAuthenticatable, CanResetPassword, MustVerifyEmailContract
{
    use Notifiable, Authenticatable;

    protected $table = "clients";
    protected $guarded = [];

    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    public function sendPasswordResetNotification($token)
    {
        
    }

    // Implementing the required methods for MustVerifyEmail
    public function getEmailForVerification()
    {
        return $this->email;
    }

    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }


    public function markEmailAsVerified()
    {
 
        $this->email_verified_at = now();
        $this->save(); 
        
        $subject = emailContentSettings('welcome')->subject ?? 'Welcome to Our Service';
        $headerContent = emailContentSettings('welcome')->header_text ?? 'Welcome, {{ first_name }}!';
        $bodyText = emailContentSettings('welcome')->body_text ?? 'We are excited to have you on board, {{ first_name }}! Your registered email is {{ email }}.';
        $footerText = emailContentSettings('welcome')->footer_text ?? 'Best regards, The WWC Team';

        $placeholders = [
            '{{ first_name }}' => GetActiveGuardDetail()->first_name,
            '{{ email }}' => GetActiveGuardDetail()->email,
        ];
        $headerContent = str_replace(array_keys($placeholders), array_values($placeholders), $headerContent);
        $bodyText = str_replace(array_keys($placeholders), array_values($placeholders), $bodyText);
        $footerText = str_replace(array_keys($placeholders), array_values($placeholders), $footerText);
        $htmlContent = view('client_auth.welcome-email', [
            'headerContent' => $headerContent,
            'bodyText' => $bodyText,
            'footerText' => $footerText,
        ])->render(); 
        SendInBlue(GetActiveGuardDetail()->email, GetActiveGuardDetail()->first_name, $subject, $htmlContent);
        return true;
    }

    public function sendEmailVerificationNotification()
    {   return;
        $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail);
    }
}
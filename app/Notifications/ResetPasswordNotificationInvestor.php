<?php

namespace App\Notifications;

use App\Models\ClientsModel; 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotificationInvestor extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $token;
    public function __construct($token)
    {
        $this->token    =   $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $email      =   $notifiable->getEmailForPasswordReset();
        $username   =   ClientsModel::selectRaw("CONCAT(first_name,' ',IFNULL(last_name,'')) as name")->whereRaw("email = '$email'")->first();
        $url        =   url(route('investor.reset', [
            'token' => $this->token,
            'email' => $email,
        ], false));
        $organization_email =   Organization::value('email');
        $organization       =   Organization::Select('youtube_link', 'linkedin_link', 'insta_link', 'fb_link')->first();

        return (new MailMessage)
            ->subject('Reset Password Notification')
            ->view('emails.investor.reset-password', ['url' => $url, 'name' => $username->name, 'organization' => $organization, 'organization_email' => $organization_email]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

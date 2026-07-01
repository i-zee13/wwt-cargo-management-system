<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;
    public $client;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
{
     
//     $client = $this->client;
 
//     $subject = emailContentSettings('welcome')->subject ?? 'Welcome to Our Service';
//     $headerContent = emailContentSettings('welcome')->header_text ?? 'Welcome, {{ client_name }}!';
//     $bodyText = emailContentSettings('welcome')->body_text ?? 'We are excited to have you on board, {{ client_name }}! Your registered email is {{ client_email }}.';
//     $footerText = emailContentSettings('welcome')->footer_text ?? 'Best regards, The WWC Team'; 
//     $placeholders = [
//         '{{ first_name }}' => $client->first_name,
//         '{{ email }}' => $client->email,
//     ]; 
//     $headerContent = str_replace(array_keys($placeholders), array_values($placeholders), $headerContent);
//     $bodyText = str_replace(array_keys($placeholders), array_values($placeholders), $bodyText);
//     $footerText = str_replace(array_keys($placeholders), array_values($placeholders), $footerText);

//     return $this
//         ->subject($subject)
//         ->view('client_auth.welcome-email')
//         ->with([
//             'headerContent' => $headerContent,
//             'bodyText' => $bodyText,
//             'footerText' => $footerText,
//         ]);
// }

}

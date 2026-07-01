<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PackageStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $record;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($client, $record)
    {
        $this->client = $client;
        $this->record = $record;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    { 
        $formattedStatus= ucwords(str_replace('-', ' ', $this->record->status)); 
        $headerContent  = emailContentSettings('status-change')->header_text;
        $bodyContent    = emailContentSettings('status-change')->body_text;
        $footerContent  = emailContentSettings('status-change')->footer_text; 
        $placeholders = [
            '{{ waybill }}' => $this->record->waybill,
            '{{ status }}' => $formattedStatus,
            '{{ client_name }}' => $this->client->first_name,
        ]; 
        $headerContent = str_replace(array_keys($placeholders), array_values($placeholders), $headerContent);
        $bodyContent = str_replace(array_keys($placeholders), array_values($placeholders), $bodyContent);
        $footerContent = str_replace(array_keys($placeholders), array_values($placeholders), $footerContent);
        
        return $this->view('client_auth.package-update') 
                    ->subject(emailContentSettings('status-change')->subject ??'Package Status Update')
                    ->with([
                        'headerContent' => $headerContent,
                        'bodyContent' => $bodyContent,
                        'footerContent' => $footerContent,
                    ]);
    }
}

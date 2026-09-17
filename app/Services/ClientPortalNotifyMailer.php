<?php

namespace App\Services;

use App\Models\ClientsModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;

class ClientPortalNotifyMailer
{
    public function send(ClientsModel $client, string $subjectTemplate, string $bodyTemplate): void
    {
        $displayName = trim((string) ($client->first_name ?? ''));
        if ($displayName === '' || strcasecmp($displayName, 'WWC') === 0) {
            $displayName = config('brand.short_name', 'WWT');
        }

        $expireMinutes = (int) config('auth.passwords.clients.expire', 60);
        $portalUrl = rtrim((string) config('app.client_url', 'https://client.wwt.com.py'), '/');
        $resetUrl = $this->makeResetUrl($client, $expireMinutes);

        $placeholders = [
            '{{ first_name }}' => $displayName,
            '{{ email }}' => (string) $client->email,
            '{{ suite }}' => (string) ($client->suite ?? ''),
            '{{ portal_url }}' => $portalUrl,
            '{{ reset_url }}' => $resetUrl,
            '{{ expire_minutes }}' => (string) $expireMinutes,
        ];

        $subject = replaceLegacyWwcBrand(str_replace(
            array_keys($placeholders),
            array_values($placeholders),
            $subjectTemplate
        ));

        $body = replaceLegacyWwcBrand(str_replace(
            array_keys($placeholders),
            array_values($placeholders),
            $bodyTemplate
        ));

        $footer = emailFooterText(emailContentSettings('reset')->footer_text ?? null);

        $html = view('emails.client-portal-notify', [
            'subject' => $subject,
            'bodyContent' => $body,
            'footerContent' => $footer,
            'resetUrl' => $resetUrl,
            'expireMinutes' => $expireMinutes,
            'portalUrl' => $portalUrl,
        ])->render();

        $ok = SendInBlue($client->email, $displayName, $subject, $html);
        if (! $ok) {
            throw new \RuntimeException(lastMailSendError() ?: 'Failed to send email');
        }
    }

    private function makeResetUrl(ClientsModel $client, int $expireMinutes): string
    {
        DB::table('password_resets')->where('email', $client->email)->delete();

        $token = Password::broker('clients')->createToken($client);

        $clientRoot = rtrim((string) config('app.client_url', config('app.url')), '/');
        $previousRoot = rtrim((string) config('app.url'), '/');

        if ($clientRoot !== '') {
            URL::forceRootUrl($clientRoot);
        }

        try {
            return URL::temporarySignedRoute(
                'client.reset',
                Carbon::now()->addMinutes($expireMinutes),
                [
                    'token' => $token,
                    'email' => $client->email,
                ]
            );
        } finally {
            if ($previousRoot !== '') {
                URL::forceRootUrl($previousRoot);
            }
        }
    }
}

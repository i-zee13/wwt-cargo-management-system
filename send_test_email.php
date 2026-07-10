<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$to = $argv[1] ?? 'Zeeshan13.ta@gmail.com';

echo 'Mailer: '.config('mail.default').PHP_EOL;
echo 'Host: '.config('mail.mailers.smtp.host').PHP_EOL;
echo 'From: '.config('mail.from.address').PHP_EOL;
echo 'User: '.config('mail.mailers.smtp.username').PHP_EOL;
echo 'Sending to: '.$to.PHP_EOL;

try {
    Illuminate\Support\Facades\Mail::raw(
        'WWT local SMTP test at '.now()->toDateTimeString(),
        function ($message) use ($to) {
            $message->to($to, 'Zeeshan')->subject('WWT Local SMTP Test');
        }
    );
    echo "SUCCESS\n";
} catch (Throwable $e) {
    echo 'FAILED: '.$e->getMessage().PHP_EOL;
    exit(1);
}

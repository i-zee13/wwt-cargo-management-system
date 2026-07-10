<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$to = $argv[1] ?? 'Zeeshan13.ta@gmail.com';

$placeholders = [
    '{{ first_name }}' => 'John',
    '{{ client_name }}' => 'John',
    '{{ email }}' => $to,
    '{{ suite }}' => 'WWTAS999',
    '{{ waybill }}' => 'USA18026 0001',
    '{{ status }}' => 'Received',
    '{{ kg }}' => '2.50',
    '{{ grand_total }}' => '45.00',
];

$headerContent = 'Hello {{ client_name }}';
$bodyContent = '<p>Package {{ waybill }} status: {{ status }}</p>';
$footerContent = 'Regards WWT';

$html = view('client_auth.package-update', [
    'headerContent' => str_replace(array_keys($placeholders), array_values($placeholders), $headerContent),
    'bodyContent' => str_replace(array_keys($placeholders), array_values($placeholders), $bodyContent),
    'footerContent' => str_replace(array_keys($placeholders), array_values($placeholders), $footerContent),
    'buttonText' => 'View Package',
])->render();

echo 'HTML length: '.strlen($html).PHP_EOL;

$result = SendInBlue($to, 'John', '[TEST] Template test', $html);
echo $result ? "SUCCESS\n" : "FAILED\n";

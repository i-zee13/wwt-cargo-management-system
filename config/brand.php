<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WWT brand identity (replaces legacy WWC / World Wide Commerce)
    |--------------------------------------------------------------------------
    */

    'name' => env('BRAND_NAME', 'World Wide Trading Group'),

    'short_name' => env('BRAND_SHORT_NAME', 'WWT'),

    'email_signature' => env('BRAND_EMAIL_SIGNATURE', 'Best regards, The WWT Team'),

    'default_logo' => env('BRAND_LOGO_PATH', 'images/wwt-logo.png'),

    'support_email' => env('BRAND_SUPPORT_EMAIL', 'consultas@wwt.com.py'),

    /*
    | Client suite / casilla prefix (was COMM under legacy branding).
    | Example: WWTAS123 for branch "Asuncion" + client id 123.
    */
    'suite_prefix' => env('BRAND_SUITE_PREFIX', 'WWT'),

];

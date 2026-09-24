<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WWC brand identity (mercalo / World Wide Commerce)
    |--------------------------------------------------------------------------
    | main branch uses WWT / World Wide Trading Group.
    | This mercalo branch keeps legacy WWC branding.
    */

    'name' => env('BRAND_NAME', 'World Wide Commerce'),

    'short_name' => env('BRAND_SHORT_NAME', 'WWC'),

    'email_signature' => env('BRAND_EMAIL_SIGNATURE', 'Best regards, The WWC Team'),

    'default_logo' => env('BRAND_LOGO_PATH', 'images/logo.png'),

    'support_email' => env('BRAND_SUPPORT_EMAIL', 'consultas@wwc.com.py'),

    /*
    | Client suite / casilla prefix for packaging.
    | Example: WWCAS123 for branch "Asuncion" + client id 123.
    */
    'suite_prefix' => env('BRAND_SUITE_PREFIX', 'WWC'),

];

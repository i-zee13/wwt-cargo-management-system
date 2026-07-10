<?php
//Laravel custom helper functions

use App\Models\ActivityMedia;
use App\Models\ActivityOptions;
use App\Models\CollectionModel;
use App\Models\LessonWizardsMediaModel;
use App\Models\PackageModel;
use App\Models\VideoLinks;
use App\Relative;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Config;


if (!function_exists('dateFormat')) {
    function dateFormat($date)
    {
        if ($date == null || $date == '')
            return 'NA';
        else
            return date('d-m-Y', strtotime($date));
    }
}

if (!function_exists('dateTimeFormat')) {
    function dateTimeFormat($date)
    {
        if ($date == null || $date == '')
            return 'NA';
        else
            return date('d-m-Y H:i', strtotime($date));
    }
}
if (!function_exists('GetActiveGuardDetail')) {
    function GetActiveGuardDetail()
    {
        $loginDetail            =   "";
        if (Auth::guard('web')->check()) {
            $loginDetail        =   Auth::user();
            if (Route::currentRouteName() != "logout") {
                $loginDetail->is_web =   1;
            }
        } else if (Auth::guard('clients')->check()) {
            $loginDetail        =   Auth::guard('clients')->user();
            if ($loginDetail) {
                $loginDetail->name  =   $loginDetail->first_name;
                $loginDetail->phone =   $loginDetail->phone;
                if (Route::currentRouteName() != "logout") {
                    $loginDetail->is_web =   0;
                }
            }
        }
        return $loginDetail;
    }
}

if (!function_exists('timeFormat')) {
    function timeFormat($date)
    {
        if ($date == null || $date == '')
            return 'NA';
        else
            return date('H:i', strtotime($date));
    }
}





if (!function_exists('getAllCountry')) {
    function getAllCountry()
    {
        return DB::table('countries')->get();
    }
}

if (!function_exists('getCountryStates')) {
    function getCountryStates($country_id)
    {
        return DB::table('states')->where('country_id', '=', $country_id)->get();
    }
}

if (!function_exists('getStateCites')) {
    function getStateCites($state_id)
    {
        return DB::table('cities')->where('state_id', '=', $state_id)->get();
    }
}
if (!function_exists('clientMacAddressForLoginLog')) {
    /**
     * MAC address is only available on some Windows CLI setups; never crash login when exec is disabled.
     */
    function clientMacAddressForLoginLog(): string
    {
        if (! function_exists('exec')) {
            return 'NA';
        }

        $disabled = array_filter(array_map('trim', explode(',', (string) ini_get('disable_functions'))));
        if (in_array('exec', $disabled, true)) {
            return 'NA';
        }

        $output = @exec('getmac 2>&1');

        if (! is_string($output) || trim($output) === '') {
            return 'NA';
        }

        return substr(trim($output), 0, 17) ?: 'NA';
    }
}

if (!function_exists('getOrganizationData')) {
    function getOrganizationData()
    {
        $defaultLogo = '/'.ltrim(config('brand.default_logo', 'images/wwt-logo.png'), '/');
        $organization = DB::table('organizations')->first();

        if (! $organization) {
            return (object) [
                'name' => config('brand.name'),
                'logo' => $defaultLogo,
                'logo_base64' => null,
            ];
        }

        if (empty($organization->name)) {
            $organization->name = config('brand.name');
        }

        $storedLogo = trim((string) ($organization->logo ?? ''));
        $relativeLogo = ltrim($storedLogo, '/');

        if ($relativeLogo !== '' && is_file(public_path($relativeLogo))) {
            $organization->logo = '/'.$relativeLogo;
            $organization->logo_base64 = 'data:image/png;base64,'.base64_encode(file_get_contents(public_path($relativeLogo)));
        } else {
            $organization->logo = $defaultLogo;
            $defaultRelative = ltrim($defaultLogo, '/');
            $organization->logo_base64 = is_file(public_path($defaultRelative))
                ? 'data:image/png;base64,'.base64_encode(file_get_contents(public_path($defaultRelative)))
                : null;
        }

        return $organization;
    }
}
if (!function_exists('emailContentSettings')) {
    function emailContentSettings($email_type)
    {
        return DB::table('email_content_settings')->where('email_type', '=', $email_type)->first();
    }
}
 
if (!function_exists('SendInBlue')) {
    /**
     * Sends HTML email using Laravel mail config (MAIL_* in .env).
     * Kept as SendInBlue() for backward compatibility with existing controllers.
     */
    function SendInBlue($reciver_email, $recever_name, $subject, $htmlContent)
    {
        try {
            \Illuminate\Support\Facades\Mail::html($htmlContent, function ($message) use ($reciver_email, $recever_name, $subject) {
                $message->to($reciver_email, $recever_name)->subject($subject);
            });

            return true;
        } catch (\Throwable $exception) {
            \Illuminate\Support\Facades\Log::error('Email send failed', [
                'to' => $reciver_email,
                'subject' => $subject,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }
}
if (!function_exists('hasUnpaidPackages')) {
    function hasUnpaidPackages(){
       if(!GetActiveGuardDetail()->is_web==1){
           $packages = PackageModel::where('client_id',GetActiveGuardDetail()->id)->where('payment_status','!=',1)->get();
           
           return $packages;
       }
       return null;
    }   
   }

if (!function_exists('emailFooterText')) {
    function emailFooterText(?string $footerFromSettings = null): string
    {
        if ($footerFromSettings && trim($footerFromSettings) !== '') {
            return $footerFromSettings;
        }

        return config('brand.email_signature');
    }
}

if (!function_exists('brandLogoUrl')) {
    function brandLogoUrl(): string
    {
        return asset(ltrim(getOrganizationData()->logo, '/'));
    }
}
 
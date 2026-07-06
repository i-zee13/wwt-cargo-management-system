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
    function clientMacAddressForLoginLog(): ?string
    {
        if (! function_exists('exec')) {
            return null;
        }

        $disabled = array_filter(array_map('trim', explode(',', (string) ini_get('disable_functions'))));
        if (in_array('exec', $disabled, true)) {
            return null;
        }

        $output = @exec('getmac 2>&1');

        if (! is_string($output) || trim($output) === '') {
            return null;
        }

        return substr(trim($output), 0, 17) ?: null;
    }
}

if (!function_exists('isLegacyOrganizationLogo')) {
    function isLegacyOrganizationLogo(?string $logo): bool
    {
        if (! $logo || trim($logo) === '') {
            return true;
        }

        $lower = strtolower($logo);

        foreach (['wwc', 'commerce', 'worldwidecommerce'] as $marker) {
            if (str_contains($lower, $marker)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('organizationLogoPath')) {
    function organizationLogoPath(?string $storedLogo = null): string
    {
        $default = ltrim(config('brand.default_logo', 'images/wwt-logo.png'), '/');

        if (isLegacyOrganizationLogo($storedLogo)) {
            $storedLogo = $default;
        } else {
            $storedLogo = ltrim((string) $storedLogo, '/');
        }

        if (! is_file(public_path($storedLogo))) {
            $storedLogo = $default;
        }

        return '/'.$storedLogo;
    }
}

if (!function_exists('getOrganizationData')) {
    function getOrganizationData()
    {
        $organization = DB::table('organizations')->first();
        if (!$organization) {
            return (object) [
                'name' => config('brand.name'),
                'logo' => organizationLogoPath(null),
                'logo_base64' => null,
            ];
        }

        $organization->name = $organization->name ?: config('brand.name');
        $organization->logo = organizationLogoPath($organization->logo ?? null);

        $logoPath = public_path(ltrim($organization->logo, '/'));
        if (is_file($logoPath)) {
            $organization->logo_base64 = 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath));
        } else {
            $organization->logo_base64 = null;
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
    function SendInBlue($reciver_email,$recever_name,$subject,$htmlContent)
    {
        $ch = curl_init();

        $data = [
       "sender" => [
           "name" => config('brand.short_name'),
           "email" => config('brand.support_email')
       ],
       "to" => [
           [
               "name"  => $recever_name,
               "email" => $reciver_email
           ]
       ],
       "subject"    => $subject,
       "htmlContent" => $htmlContent
   ];
   
   curl_setopt($ch, CURLOPT_URL, "https://api.sendinblue.com/v3/smtp/email");
   curl_setopt($ch, CURLOPT_HTTPHEADER, [
       "api-key: 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",   
       "Content-Type: application/json"
   ]);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   
   $response = curl_exec($ch);
   curl_close($ch);
   return ;
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
        $organization = DB::table('organizations')->first();
        return asset(organizationLogoPath($organization->logo ?? null));
    }
}
 
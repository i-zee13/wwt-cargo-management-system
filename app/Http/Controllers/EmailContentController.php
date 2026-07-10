<?php

namespace App\Http\Controllers;

use App\Country;
use App\Models\Branches;
use App\Models\ClientsModel;
use App\Models\DocumentTypes;
use App\Models\EmailContentSettings;
use App\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

class EmailContentController extends Controller
{
    public function verification_email()
    {
        $data = $this->emailContent('verification');
        
        return view('admin.emails-content.index', $data);
    }
    public function package_create()
    { 
        $data = $this->emailContent('create-package'); 
        return view('admin.emails-content.index', $data);
    }

    public function welcome_email()
    {
        $data = $this->emailContent('welcome');
        return view('admin.emails-content.index', $data);
    }

    public function status_change_email()
    {
        $data = $this->emailContent('status-change');
        return view('admin.emails-content.index', $data);
    }

    public function emailContent($type)
    {  
        $emailTypes = [
            'verification' => 'Verification Email Content',
            'welcome' => 'Welcome Email Content',
            'status-change' => 'Package Status Email Content',
            'create-package' => 'Package Create Email Content',
        ];

        if (!array_key_exists($type, $emailTypes)) {
            abort(404);
        }
        $jsonContent ='';
        $pageTitle = $emailTypes[$type];
        $formTitle = "{$pageTitle} Details";
        $record = DB::table('email_content_settings')->where('email_type', $type)->first();
        
       
        return compact('pageTitle', 'formTitle', 'type', 'record');
    }
    public function store(Request $request)
    {   
        $pageType = $request->pageType; 
        if (!$pageType || !in_array($pageType, ['verification', 'welcome', 'status-change','create-package'])) {
            return response()->json(['status' => 'error', 'msg' => 'Invalid Request. Please reload the form!']);
        } 
        $messages = [
            'verification'  => 'Verification Email Content Saved Successfully',
            'status-change' => 'Package Status Email Content Saved Successfully',
            'welcome'       => 'Welcome Email Content Saved Successfully',
            'create-package'       => 'Package Create Email Content Saved Successfully',
        ];
        $msg = $messages[$pageType]; 
        $record = EmailContentSettings::where('email_type', $pageType)->first() ?? new EmailContentSettings();
        $record->email_type  = $request->pageType; 
        $record->header_text = $request->headerText; 
        $record->footer_text = $request->footerText; 
        $record->body_text   = $request->body_text;  
        $record->button_text = $request->buttonText;  
        $record->subject     = $request->subject;  
        $record->save(); 
        return response()->json(['status' => 'success', 'msg' => $msg]);
    }

    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email|max:255',
            'pageType' => 'required|in:verification,welcome,status-change,create-package',
            'subject' => 'required|string|max:255',
            'headerText' => 'required|string|max:500',
            'buttonText' => 'required|string|max:255',
            'footerText' => 'required|string|max:1000',
            'body_text' => 'required|string',
        ]);

        $placeholders = $this->dummyEmailPlaceholders($request->test_email);

        $subject = str_replace(array_keys($placeholders), array_values($placeholders), $request->subject);
        $headerContent = str_replace(array_keys($placeholders), array_values($placeholders), $request->headerText);
        $bodyContent = str_replace(array_keys($placeholders), array_values($placeholders), $request->body_text);
        $footerContent = str_replace(array_keys($placeholders), array_values($placeholders), $request->footerText);
        $footerContent = emailFooterText($footerContent);

        $htmlContent = match ($request->pageType) {
            'verification' => view('client_auth.verification-email-temp', [
                'headerContent' => $headerContent,
                'bodyContent' => $bodyContent,
                'footerContent' => $footerContent,
                'url' => config('app.url').'/customer-login',
                'buttonText' => $request->buttonText,
            ])->render(),
            'welcome' => view('client_auth.welcome-email', [
                'headerContent' => $headerContent,
                'bodyText' => $bodyContent,
                'footerText' => $footerContent,
                'buttonText' => $request->buttonText,
            ])->render(),
            default => view('client_auth.package-update', [
                'headerContent' => $headerContent,
                'bodyContent' => $bodyContent,
                'footerContent' => $footerContent,
                'buttonText' => $request->buttonText,
            ])->render(),
        };

        $sent = SendInBlue(
            $request->test_email,
            $placeholders['{{ client_name }}'],
            '[TEST] '.$subject,
            $htmlContent
        );

        if (! $sent) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Failed to send test email. Check mail settings and logs.',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'msg' => 'Test email sent to '.$request->test_email,
        ]);
    }

    private function dummyEmailPlaceholders(string $testEmail): array
    {
        return [
            '{{ first_name }}' => 'John',
            '{{ client_name }}' => 'John',
            '{{ email }}' => $testEmail,
            '{{ suite }}' => 'WWTAS999',
            '{{ waybill }}' => 'USA18026 0001',
            '{{ status }}' => 'Received',
            '{{ kg }}' => '2.50',
            '{{ grand_total }}' => '45.00',
            '{{ original_tracking }}' => 'GFUS01058293779011',
            '{{ description }}' => 'Test package description',
            '{{ comments }}' => 'Test package comments',
        ];
    }


}

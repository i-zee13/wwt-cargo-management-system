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
    

}

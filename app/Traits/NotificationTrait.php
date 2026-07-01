<?php

namespace App\Traits;

use App\Events\NotificationSending;
use App\Mail\LeadAssignment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Swift_TransportException;
use DB;
use Auth;
use Carbon\Carbon;

trait NotificationTrait{

    public function webNotification($request,$notification_action_id,$save_lead_id=null){
        $inquiry_access         =   DB::table('user_subscribed_notifications')->WHERE('notification_type_id',$notification_action_id)->WHERE('notifiable','1')->WHERE('user_id',$request->assigned_to_lead ? $request->assigned_to_lead: $request->assign_to)->get();
        // dd($inquiry_access);
        $msg                    =   "";
        if($notification_action_id == '2'){
            $header             =   "Lead Assignment";
            $content            =   ucwords(Auth::user()->name)." has assigned a New Lead to you";
        }
        if(count($inquiry_access) > 0){
            $sender_name        = $notification_action_id.'-'.$header.'-'.' ';
            $data               = $sender_name.'-'.$content;
            foreach($inquiry_access as $access){
                DB::table('all_notifications_list')->insert([
                    'notification_action_id'=>  $notification_action_id,
                    'notification_type'     =>  '1',
                    'lead_id'               =>  $request->casefile_id ? $request->casefile_id : $save_lead_id,
                    'emp_id'                =>  $access->user_id,
                    'message'               =>  $data,
                    'created_by'            =>  Auth::user()->id,
                    'created_at'            =>  Carbon::now()->format('Y-m-d H:i:s')
                ]);
            }
            event(new NotificationSending($data,$inquiry_access));
        } 
    }
    public function emailNotification($request,$page_reference,$notification_action_id,$save_lead_id = null){
        $inquiry_access_email   =   DB::table('user_subscribed_notifications')->selectRaw('*,
                                        (SELECT email from users WHERE id=user_subscribed_notifications.user_id) as email,
                                        (SELECT name from users WHERE id=user_subscribed_notifications.user_id) as username
                                    ')
                                    ->WHERE('notification_type_id',$notification_action_id)->WHERE('email','1')->WHERE('user_id',$request->assigned_to_lead ? $request->assigned_to_lead: $request->assign_to)
                                    ->get();
        if($notification_action_id == '2'){
            $header             =   "Lead Assignment";
            $content            =   ucwords(Auth::user()->name)." has assigned a New Lead to you";
        }
        if(count($inquiry_access_email) > 0){
            $sender_name        = $notification_action_id.'-'.$header.'-'.' ';
            $data               = $sender_name.'-'.$content;
            foreach($inquiry_access_email as $access){
                DB::table('all_notifications_list')->insert([
                    'notification_action_id'=>  $notification_action_id,
                    'notification_type'     =>  '2',
                    'lead_id'               =>  $request->casefile_id ? $request->casefile_id : $save_lead_id,
                    'emp_id'                =>  $access->user_id,
                    'message'               =>  $data,
                    'created_by'            =>  Auth::user()->id,
                    'created_at'            =>  Carbon::now()->format('Y-m-d H:i:s')
                ]);
                $this->sendLeadAssignmentEmail($access->email,$access->username,$page_reference,$request);
            }
        }
    }
    public function sendLeadAssignmentEmail($email,$username,$page_reference,$request){
        // Email Data
        $data_email['client']               =   $username;
        $data_email['assigne_name']         =   Auth::user()->name;
        $data_email['lead_name']            =   $request->client_first_name ? $request->client_first_name : ($request->first_name.' '.$request->client_last_name ? $request->client_last_name : ($request->last_name));
        $data_email['lead_email']           =   $request->client_email ? $request->client_email : $request->email;
        $data_email['lead_phone']           =   $request->client_primary_cellphone ? $request->client_primary_cellphone : str_replace(' ','',str_replace('-', '',str_replace('(', '', str_replace(')', '', $request->phone_no))));;
        $data_email['subject']              =   "New Lead Assigned";
        $data_email['page_reference']       =   $page_reference;
        $data_email['lead_link']            =   "https://crm.khanllp.com/Correspondence/create/lead/".$request->casefile_id;
        try{ Mail::to($email)->send(new LeadAssignment($data_email));
            return true;
        }
        catch(Swift_TransportException $e){
            Log::error($e->getMessage());
            return false;   
        }
    }
}
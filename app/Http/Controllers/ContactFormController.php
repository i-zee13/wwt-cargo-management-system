<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.contact_forms.contact_forms');
    }
    public function subscribers_page()
    {

        return view('admin.contact_forms.subscribers-list');
    }
    public function subscribers_list()
    {
        $subscribers = DB::table('newsletter_subscription_list')->orderby('id', 'DESC')->get();
        return response()->json([
            'status' => 'success',
            'msg' => 'Subscribers Fetched Successfully',
            'subscribers' => $subscribers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchInquiries(Request $request)
    {
        $where     =    '';
        if ($request->status != null) {
            $where =    "AND status = '$request->status'";
        }
        $sql = "SELECT *, 
         CASE 
            WHEN status = 1 THEN 'Contacted'
            WHEN status = 2 THEN 'Spamed'
            WHEN status = 3 THEN 'Deleted'
            ELSE 'Pending'
            END AS inquiry_status
        FROM contact_forms 
        WHERE DATE(submission_date) BETWEEN '$request->from_submission_date' AND '$request->to_submission_date' 
        $where 
        ORDER BY id DESC";
        $inquiries = DB::select($sql);
        return response()->json([
            'status'    => 'success',
            'msg'       => 'Inquiries Fetched Successfully',
            'inquiries' => $inquiries
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request)
    {
        DB::table('contact_forms')
            ->where('id', $request->id)
            ->update(['status' => $request->status]);
        return response()->json([
            'status' => 'success',
            'msg' => 'Inquiry Status Updated Successfully'
        ]);
    }
}

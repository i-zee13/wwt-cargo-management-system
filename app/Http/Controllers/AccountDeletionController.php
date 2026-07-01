<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountDeletionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.account_deletion_requests.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchRequests(Request $request)
    {
        $where = '';
        if ($request->status != null) {
            $where = "AND account_deletion_requests.status = '$request->status'";
        }

        $sql = "SELECT account_deletion_requests.*, 
                       CONCAT(users.first_name, ' ', users.last_name) AS customer_name, 
                       users.email,
                       CASE 
                           WHEN account_deletion_requests.status = 1 THEN 'Accepted'
                           WHEN account_deletion_requests.status = 0 THEN 'Pending' 
                           ELSE 'unknown'
                       END AS request_status
                FROM account_deletion_requests 
                JOIN users ON users.id = account_deletion_requests.customer_id
                WHERE DATE(account_deletion_requests.created_at) = '$request->date'
                $where ORDER BY account_deletion_requests.id DESC"; 

        $deletionRequests = DB::select($sql);
        return response()->json([
            'status' => 'success',
            'msg' => 'Requests Fetched Successfully',
            'deletionRequests' => $deletionRequests
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
        $customer_status = $request->status == 1 ? 0 : 1;
        DB::table('account_deletion_requests')
            ->join('customer', 'account_deletion_requests.customer_id', '=', 'customer.id')
            ->where('account_deletion_requests.id', $request->id)
            ->update([
                'account_deletion_requests.status' => $request->status,
                'customer.status' => $customer_status,
            ]);
        return response()->json([
            'status'         => 'success',
            'msg'            => 'Account Deletion Status Updated Successfully',
            'account_status' => $request->status
        ]);
    }
}

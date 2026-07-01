<?php

namespace App\Http\Controllers;

use App\Exports\PackagesExport;
use App\Http\Controllers\Core\AccessRightsAuth;
use App\Models\Branches;
use App\Models\ClientsModel;
use App\Models\OriginModel;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportingController extends AccessRightsAuth
{

    public function reporting()
    {
        $clients = ClientsModel::all();
        $origins = OriginModel::get();
        $branches = Branches::where('status', 1)->get();
        return view('reporting.index', compact('origins', 'branches', 'clients'));
    }
    public function generate_report(Request $request)
    {    
        $totalGrandTotal = 0;
        $totalKg  = 0;
        $origins = $request->origins;
        $branches = $request->branches;
        $statuses = $request->statuses;
        $types = $request->types;
        $clients = $request->clients;
        $filter_date = $request->filter_date;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $query = DB::table('packages')
        ->leftjoin('clients', 'packages.client_id', '=', 'clients.id') // Join with clients
        ->leftjoin('origins', 'packages.origin_id', '=', 'origins.id') // Join with origins
        ->leftjoin('branches', 'clients.branch_id', '=', 'branches.id') // Join with branches
        ->select(
            'packages.id AS guide',           
            'packages.waybill AS waybill',         
            'origins.origin_name AS origin',       
            'packages.description AS description', 
            'packages.type AS type',             
            'packages.original_tracking AS tracking', 
            'packages.created_at AS date',    
            'packages.kg AS kg',                
            'packages.cbm AS cbm',               
            'clients.first_name AS client',      
            'clients.last_name AS client_last',    
            'clients.suite AS suite',    
            'branches.branch AS branch',           
            'packages.status AS status',         
            'packages.grand_total AS total',      
            'packages.status_change_date AS delivered'  
        ); 
    if ($origins && $origins[0] != 'all') {
        $query->whereIn('packages.origin_id', $origins);
    }
    if ($branches && $branches[0] != 'all') {
        $query->whereIn('clients.branch_id', $branches);
    }
    if ($statuses && $statuses[0] != 'all') {
         
        $query->whereIn('packages.status', $statuses);
    }
    if ($types && $types[0] != 'all') {
        $query->whereIn('packages.type', $types);
    }
    if ($clients && $clients[0] != 'all') {
        $query->whereIn('packages.client_id', $clients);
    }
    if ($filter_date != 1) { 
        if ($start_date && $end_date) { 
            $start_date = \Carbon\Carbon::parse($start_date)->startOfDay();   
            $end_date = \Carbon\Carbon::parse($end_date)->endOfDay(); 
            $query->whereBetween('packages.created_at', [$start_date, $end_date]);
        }
    }
    $packages = $query->get(); 
    if($packages){
        $totalKg = $packages->sum('kg');  
        $totalGrandTotal = $packages->sum('total');
    }

  
    return response()->json([
        'status' => 'success',
        'data' => $packages,
        'totalKg' => $totalKg,
        'totalGrandTotal' => $totalGrandTotal,
    ]);


    }
    public function exportExcel(Request $request)
    {
        
        $filters = $request->only([
            'origins',
            'branches',
            'statuses',
            'types',
            'clients',
            'filter_date',
            'start_date',
            'end_date'
        ]); 
        $organization = getOrganizationData(); 
        if (!$organization) {
            return redirect()->back()->with('error', 'Organization data not found.');
        }  
        $export = new PackagesExport(
            $filters,
            $organization->name, 
            $organization->logo_base64  
        ); 
        return Excel::download($export, 'packages_report.xlsx');
    }
}

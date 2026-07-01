<?php

namespace App\Http\Controllers;

use App\Models\ClientsModel;
use App\Models\PackageModel;
use App\Models\OriginModel; 
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Core\AccessRightsAuth;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use stdClass;

class HomeController extends AccessRightsAuth
{
    public $controllerName = "HomeController";

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeLanguage(Request $request){
        dd($request->all());
    }
    public function index(Request $request){
       
        error_reporting(0);
        $todayDate          =   Carbon::today();
        if($request->date){
            $todayDate = $request->date;
        } 
 
        $activeClients      =   ClientsModel::whereDate('created_at', '<=', $todayDate)->count();
        $packagesProcessing =   PackageModel::where('status','in-progress')->whereDate('status_change_date',$todayDate)->count();
        $packagesCreated    =   PackageModel::whereDate('created_at',$todayDate)->count();
        $packagesDelivered  =   PackageModel::where('status','retired')->whereDate('status_change_date',$todayDate)->count();  
        $deliveredPackagesWeight = PackageModel::where('status', 'retired')
                                    ->whereDate('status_change_date', $todayDate)
                                    ->sum('kg');
        $currentHour        =   Carbon::now()->format('H');
        $morningGreeting    =   'Good Morning';
        $afternoonGreeting  =   'Good Afternoon';
        $eveningGreeting    =   'Good Evening';
        if ($currentHour >= 5 && $currentHour < 12) {
            $greeting       =   $morningGreeting;
        } elseif ($currentHour >= 12 && $currentHour < 17) {
            $greeting       =   $afternoonGreeting;
        } else {
            $greeting       =   $eveningGreeting;
        }
        $current_date       =   Carbon::now()->format('Y-m-d');
        $days               =   [];
        for ($i = 0; $i < 7; $i++) {
            $date           =   Carbon::parse($current_date)->addDays($i)->format('Y-m-d');
            $days[]         =   $date;
        }
       
        
        if (FacadesAuth::user()->password_changed != 1) {
            return view('auth.reset-password-first');
        }  
        if(!$request->date){
            return view('home', compact('todayDate','greeting', 'days','activeClients','packagesProcessing','packagesCreated','packagesDelivered','deliveredPackagesWeight')); //->with('data', $data)->with('activities', $activities)->with('my_designation_rights', $my_designation_rights);
        }else{
            return response()->json([
                'status' => 'success',
                'msg' => 'Packages Fetched Successfully',
                'activeClients' => $activeClients,
                'packagesProcessing' => $packagesProcessing,
                'packagesCreated' => $packagesCreated,
                'packagesDelivered' => $packagesDelivered,
                'deliveredPackagesWeight' => $deliveredPackagesWeight,
                'days' => $days,
            ]);
        }

    }
    public function customer_packages(){
        return view('packages.index'); 
    }
   public function customer_index(){
         
        $todayDate          =   Carbon::today();
        $currentHour        =   Carbon::now()->format('H');
        $morningGreeting    =   'Good Morning';
        $afternoonGreeting  =   'Good Afternoon';
        $eveningGreeting    =   'Good Evening';
        if ($currentHour >= 5 && $currentHour < 12) {
            $greeting       =   $morningGreeting;
        } elseif ($currentHour >= 12 && $currentHour < 17) {
            $greeting       =   $afternoonGreeting;
        } else {
            $greeting       =   $eveningGreeting;
        }
        $current_date       =   Carbon::now()->format('Y-m-d');
        $days               =   [];
        for ($i = 0; $i < 7; $i++) {
            $date           =   Carbon::parse($current_date)->addDays($i)->format('Y-m-d');
            $days[]         =   $date;
        }
        $packagesReceived = PackageModel::where('client_id', GetActiveGuardDetail()->id)
        ->whereIn('status', ['received', 'embarked'])
        ->count();

        $packagesProgress   =   PackageModel::where('client_id',GetActiveGuardDetail()->id)
                                ->where('status','in-progress')->count();
        $packagesArrived    =   PackageModel::where('client_id',GetActiveGuardDetail()->id)
                                ->where('status','arrived')->count();
                                
        $origins            =   OriginModel::selectRaw("
                                origin_name,
                                address,
                                email,zip_code,
                                phone,
                                (SELECT name FROM countries WHERE countries.id = origins.country_id) as country_name,
                                (SELECT iso FROM countries WHERE countries.id = origins.country_id) as iso,
                                (SELECT name FROM states WHERE states.id = origins.state_id) as state_name,
                                (SELECT name FROM cities WHERE cities.id = origins.city_id) as city_name
                            ")->get();  
        return view('cus-home', compact('greeting', 'days','origins','packagesArrived','packagesProgress','packagesReceived')); 
    }
    public function ResetPasswordFirst()
    {
        return view('auth.reset-password-first');
    }
    public function UpdatePasswordFirst(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $employee = User::find(FacadesAuth::user()->id);

        if ($employee) {
            $employee->password = bcrypt($request->password);
            $employee->password_changed = '1';

            if ($employee->save()) {
                echo json_encode("success");
                return redirect('/');
            } else {
                return response()->json("failed");
            }
        } else {
            return response()->json("failed");
        }
    }

    public function view_all_activities()
    {
        return view('includes.view_all_activities', ['users' => DB::table('users')->get()]);
    }

    public function GetActivities(Request $request)
    {
        $yesterday = date("j", strtotime("yesterday"));
        $current_month = date('m');
        $last_month = (date('m') == 1 ? 12 : date('m') - 1);
        $current_year = date('Y');
        $today = date('d');

        $query_for_order = ($request->date == 0 ? 'id > 0' : ($request->date == 1 ? "Day(created_at) = $yesterday AND Month(created_at) = $current_month AND Year(created_at) = $current_year OR Day(updated_at) = $yesterday AND Month(updated_at) = $current_month AND Year(updated_at) = $current_year OR Day(completed_at) = $yesterday AND Month(completed_at) = $current_month AND Year(completed_at) = $current_year OR Day(processed_at) = $yesterday AND Month(processed_at) = $current_month AND Year(processed_at) = $current_year" : ($request->date == 2 ? "Day(created_at) = $today AND Month(created_at) = $current_month AND Year(created_at) = $current_year OR Day(updated_at) = $today AND Month(updated_at) = $current_month AND Year(updated_at) = $current_year OR Day(completed_at) = $today AND Month(completed_at) = $current_month AND Year(completed_at) = $current_year OR Day(processed_at) = $today AND Month(processed_at) = $current_month AND Year(processed_at) = $current_year" : ($request->date == 3 ? "Month(created_at) = $current_month AND Year(created_at) = $current_year OR Month(updated_at) = $current_month AND Year(updated_at) = $current_year OR Month(completed_at) = $current_month AND Year(completed_at) = $current_year OR Month(processed_at) = $current_month AND Year(processed_at) = $current_year" : ($request->date == 4 ? "Month(created_at) = $last_month AND Year(created_at) = $current_year OR Month(updated_at) = $last_month AND Year(updated_at) = $current_year OR Month(completed_at) = $last_month AND Year(completed_at) = $current_year OR Month(processed_at) = $last_month AND Year(processed_at) = $current_year" : "DATE(created_at) BETWEEN '$request->start_date' AND '$request->end_date' OR DATE(updated_at) BETWEEN '$request->start_date' AND '$request->end_date' OR DATE(completed_at) BETWEEN '$request->start_date' AND '$request->end_date' OR DATE(processed_at) BETWEEN '$request->start_date' AND '$request->end_date'")))));

        $query_date = ($request->date == 0 ? 'id > 0' : ($request->date == 1 ? "Day(created_at) = $yesterday AND Month(created_at) = $current_month AND Year(created_at) = $current_year OR Day(updated_at) = $yesterday AND Month(updated_at) = $current_month AND Year(updated_at) = $current_year" : ($request->date == 2 ? "Day(created_at) = $today AND Month(created_at) = $current_month AND Year(created_at) = $current_year OR Day(updated_at) = $today AND Month(updated_at) = $current_month AND Year(updated_at) = $current_year" : ($request->date == 3 ? "Month(created_at) = $current_month AND Year(created_at) = $current_year OR Month(updated_at) = $current_month AND Year(updated_at) = $current_year" : ($request->date == 4 ? "Month(created_at) = $last_month AND Year(created_at) = $current_year OR Month(updated_at) = $last_month AND Year(updated_at) = $current_year" : "DATE(created_at) BETWEEN '$request->start_date' AND '$request->end_date' OR DATE(updated_at) BETWEEN '$request->start_date' AND '$request->end_date'")))));


        $activities = [
            'orders' => DB::table('orders as o')->selectRaw('id, (Select company_name from customers where id = o.customer_id) as customer_name, total_amount, currency, employee_id as created_by_id, updated_by as updated_by_id, completed_by as completed_by_id, processed_by as processed_by_id, Date(created_at) as created_at, Date(updated_at) as updated_at, Date(completed_at) as completed_at, Date(processed_at) as processed_at, (Select name from users where id = o.employee_id) as created_by, (Select name from users where id = o.updated_by) as updated_by, (Select name from users where id = o.processed_by) as processed_by, (Select name from users where id = o.completed_by) as completed_by ')->whereRaw($query_for_order)->get(),

            'items' => DB::table('product_related_items as pi')->selectRaw('id, name, product_sku, (Select name from brand_related_products where sku = pi.product_sku) as product_name, created_by as created_by_id, updated_by as updated_by_id, Date(created_at) as created_at, Date(updated_at) as updated_at, (Select name from users where id = pi.created_by) as created_by, (Select name from users where id = pi.updated_by) as updated_by ')->whereRaw($query_date)->get(),

            'products' => DB::table('brand_related_products as bp')->selectRaw('name, id, sku, brand_id, created_by as created_by_id, updated_by as updated_by_id, Date(created_at) as created_at, Date(updated_at) as updated_at, (Select name from users where id = bp.created_by) as created_by, (Select name from users where id = bp.updated_by) as updated_by ')->whereRaw($query_date)->get(),

            'customers' => DB::table('customers as cust')->selectRaw('id, company_name, country_id as    country, created_by as created_by_id, updated_by as updated_by_id, Date(created_at) as created_at, Date(updated_at) as updated_at, (Select name from users where id = cust.created_by) as created_by, (Select name from users where id = cust.updated_by) as updated_by ')->whereRaw($query_date)->get(),

            'pocs' => DB::table('company_poc_list as poc')->selectRaw('customer_id, first_name, (Select company_name from customers where id = poc.customer_id) as customer_name, (Select country_id as country from customers where id = poc.customer_id) as cust_country, created_by as created_by_id, updated_by as updated_by_id, Date(created_at) as created_at, Date(updated_at) as updated_at, (Select name from users where id = poc.created_by) as created_by, (Select name from users where id = poc.updated_by) as updated_by ')->whereRaw($query_date)->get(),

            'suppliers' => DB::table('supplier as sp')->selectRaw('company_name, created_by as created_by_id, updated_by as updated_by_id, Date(created_at) as created_at, Date(updated_at) as updated_at, (Select name from users where id = sp.created_by) as created_by, (Select name from users where id = sp.updated_by) as updated_by ')->whereRaw($query_date)->get(),

            'forwarders' => DB::table('forwarder as fwd')->selectRaw('company_name, created_by as created_by_id, updated_by as updated_by_id, Date(created_at) as created_at, Date(updated_at) as updated_at, (Select name from users where id = fwd.created_by) as created_by, (Select name from users where id = fwd.updated_by) as updated_by ')->whereRaw($query_date)->get(),

            'shippers' => DB::table('shipping_company as sc')->selectRaw('company_name, created_by as created_by_id, updated_by as updated_by_id, Date(created_at) as created_at, Date(updated_at) as updated_at, (Select name from users where id = sc.created_by) as created_by, (Select name from users where id = sc.updated_by) as updated_by ')->whereRaw($query_date)->get(),

            'employees' => DB::table('users as u')->selectRaw('name, created_by as created_by_id, updated_by as updated_by_id, Date(created_at) as created_at, Date(updated_at) as updated_at, (Select name from users where id = u.created_by) as created_by, (Select name from users where id = u.updated_by) as updated_by ')->whereRaw($query_date)->get(),

            'payments' => DB::table('payments as p')->selectRaw('created_by as created_by_id, updated_by as updated_by_id,Date(created_at) as created_at, Date(updated_at) as updated_at, (Select name from users where id = p.created_by) as created_by, (Select name from users where id = p.updated_by) as updated_by ')->whereRaw($query_date)->get(),

            'tasks' => DB::table('correspondences as cor')->selectRaw('created_by as created_by_id, updated_by as updated_by_id, Date(created_at) as created_at, Date(updated_at) as updated_at, Date(completed_at) as completed_at, (Select name from users where id = (Select completed_by from completed_tasks where task_id = cor.id)) as completed_by, (Select completed_by from completed_tasks where task_id = cor.id) as completed_by_id, (Select name from users where id = cor.created_by) as created_by, (Select name from users where id = cor.updated_by) as updated_by')->whereRaw('type = "task" AND ' . $query_date)->get()
        ];


        echo json_encode($activities);
    }

    public function GetSiteSearchResult($str)
    {
        $clients   = DB::table('client_main_details')->whereRaw('first_name like "' . '%' . $str . '%' . '" OR middle_name like "' . '%' . $str . '%' . '" OR last_name like "' . '%' . $str . '%' . '" OR email like "' . '%' . $str . '%' . '" OR primary_cellphone like "' . '%' . $str . '%' . '"')->get();
        $data      = array('data' => array('Clients' => $clients));

        echo json_encode($data);
    }

    function get_time_ago($time)
    {
        $time_difference = time() - $time;

        if ($time_difference < 1) {
            return 'less than 1 second ago';
        }
        $condition = array(
            12 * 30 * 24 * 60 * 60  =>  'year',
            30 * 24 * 60 * 60       =>  'month',
            24 * 60 * 60            =>  'day',
            60 * 60                 =>  'hour',
            60                      =>  'minute',
            1                       =>  'second'
        );

        foreach ($condition as $secs => $str) {
            $d = $time_difference / $secs;

            if ($d >= 1) {
                $t = round($d);
                return $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
            }
        }
    }



    public function homeRecords()
    {
        $current_date               =   Carbon::now()->format('Y-m-d');
        $web_inquiries              =   DB::select("
                                            SELECT 
                                                count(cf.id) as total_inquiries
                                            FROM 
                                            contact_form cf
                                            WHERE
                                            DATE(cf.created_at) = '$current_date'
                                            AND
                                            cf.status = 1
                                        ");
        $web_leads                  =   DB::select("
                                            SELECT 
                                                count(cf.id) as total_webleads
                                            FROM 
                                            contact_form cf
                                            WHERE
                                            DATE(cf.reviewed_at) = '$current_date'
                                            AND
                                            cf.status = 2
                                        ");
        $intake_sub                 =   DB::select("
                                            SELECT 
                                                count(cif.id) as total_intake_submission
                                            FROM 
                                            client_intake_form cif
                                            WHERE
                                            DATE(cif.submitted_at) = '$current_date'
                                            AND
                                            (cif.status = 4 OR cif.status = 2)
                                        ");
        $casefiles                  =   DB::select("
                                            SELECT 
                                                caf.*,
                                                IFNULL(lead_id,'NA') as lead_id,
                                                IFNULL(company_id,'NA') as company_id,
                                                IFNULL(sub_sec_service_id,'NA') as sub_sec_service_id,
                                                IFNULL(updated_by,'NA') as updated_by,
                                                (select CONCAT(first_name,' ',last_name) FROM client_main_details WHERE id=caf.client_id) AS client_name,
                                                IFNULL((select casefile_closing_date FROM intake_forms WHERE id=caf.intake_form_id),'NA') AS casefile_closing_date,
                                                IFNULL((select requisition_date FROM intake_forms WHERE id=caf.intake_form_id),'NA') AS requisition_date,
                                                (SELECT intake_flag FROM intake_form_types WHERE id= caf.intake_form_type_id) as intake_flag,
                                                (SELECT name FROM intake_form_types WHERE id= caf.intake_form_type_id) as intake_type_name
                                            FROM
                                            case_files caf
                                            ORDER BY caf.id desc
                                        ");
        $records                        =   new stdClass();
        $records->ttl_web_inquiries     =   $web_inquiries[0]->total_inquiries;
        $records->ttl_web_leads         =   $web_leads[0]->total_webleads;
        $records->ttl_intakes           =   $intake_sub[0]->total_intake_submission;
        $records->ttl_casefiles         =   collect($casefiles)->WHERE('status', 1)->count();
        $records->real_estate_casefiles =   collect($casefiles)->WHERE('intake_flag', '<', 6);
        $records->will_poa_casefiles    =   collect($casefiles)->WHERE('intake_flag', '>', 5);
        return response()->JSON([
            'status'    =>  'success',
            'data'      =>  $records
        ]);
    }
    public function homeCalenderRecords($date)
    {
        $user_id            =   Auth::user()->id;
        $data               =   [];
        $followup_records   =   DB::select("
                                    SELECT 
                                        (select CONCAT(first_name,' ',last_name) FROM contact_form WHERE id= (SELECT lead_id FROM correspondences WHERE id = leads_followup.correspondence_id)) as client_name,
                                        DATE_FORMAT(reminder_date_time,'%H:%i %p') as time
                                    FROM
                                    leads_followup
                                    WHERE
                                    date(reminder_date_time) = '$date'
                                    AND
                                    assign_to_id = $user_id
                                ");
        $meetings           =   DB::select("
                                    SELECT 
                                        (select CONCAT(first_name,' ',last_name) FROM client_main_details WHERE id=ifms.client_id) AS client_name,
                                        DATE_FORMAT(ifm.meeting_time,'%H:%i %p') as time
                                    FROM
                                    intake_form_meeting_assignment ifms
                                    INNER JOIN intake_form_meetings ifm ON ifms.meeting_id = ifm.id AND ifm.meeting_date = '$date' AND ifm.status = 1
                                    WHERE
                                    (ifms.primary_lawyer_id = $user_id OR ifms.secondary_lawyer_id = $user_id) 
                                ");
        $closing_dates          =   DB::select("
                                    SELECT 
                                        ifo.casefile_closing_date as casefile_closing_date,
                                        cf.id as casefile_id
                                    FROM
                                    case_files cf
                                    INNER JOIN intake_forms ifo ON cf.intake_form_id = ifo.id AND ifo.casefile_closing_date = '$date'
                                    WHERE
                                    cf.status = 1
                                ");
        $requisition_date       =   DB::select("
                                    SELECT 
                                        ifo.requisition_date as requisition_date,
                                        cf.id as casefile_id
                                    FROM
                                    case_files cf
                                    INNER JOIN intake_forms ifo ON cf.intake_form_id = ifo.id AND ifo.requisition_date = '$date'
                                    WHERE
                                    cf.status = 1
                                ");
        $data['followup']       =   $followup_records;
        $data['meetings']       =   $meetings;
        $data['closing']        =   $closing_dates;
        $data['requisition']    =   $requisition_date;
        return response()->JSON([
            'status'        =>  'success',
            'data'          =>  $data
        ]);
    }
    function unique_multidim_array($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }
}

<?php

namespace App\Http\Controllers;


use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Core\AccessRightsAuth;
use Auth;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use stdClass;

class DashboardController extends AccessRightsAuth
{
    
    public function dashboard(){ 
        return view('dashboard.dashboard');
    }
    function getAllDatesBetweenDates($startDate, $endDate) {
        $startDateObj   =   new DateTime($startDate);
        $endDateObj     =   new DateTime($endDate);
        $interval       =   new DateInterval('P1D'); // 1 day interval
        $dates          =   [];
        $currentDate    =   clone $startDateObj;
        while ($currentDate <= $endDateObj) {
            $dates[]    =   ['date'=>$currentDate->format('Y-m-d'),'day'=>$currentDate->format('d-M')];
            $currentDate->add($interval);
        }
        return $dates;
    }
    public function getDateDifference($start_date,$end_date){
        $start_date             =   new DateTime($start_date);
        $end_date               =   new DateTime($end_date);
        $interval               =   $start_date->diff($end_date);
        $totalDays              =   $interval->days + 1;
        $previous_dates         =   [];
        for ($i = 0; $i < $totalDays; $i++) {
            $previous_dates[]   =   $start_date->sub(new DateInterval('P1D'))->format('Y-m-d');
        }
        $previous_dates         =   array_reverse($previous_dates);
        return $previous_dates;
    }
    public function dashboardRecords(Request $request){
        $start_date                     =   $request->input('start_date');
        $end_date                       =   $request->input('end_date');
        $dates                          =   $this->getAllDatesBetweenDates($start_date,$end_date);
        $previous_dates                 =   $this->getDateDifference($start_date,$end_date);
        $start_p_date                   =   reset($previous_dates);
        $end_p_date                     =   end($previous_dates);
        $basic_records                  =   DB::select("
                                                SELECT 
                                                    cf.status,
                                                    cf.page_reference,
                                                    DATE_FORMAT(created_at,'%d-%b') as sub_date,
                                                    DATE(cf.created_at) as created_at,
                                                    (SELECT COUNT(id) as submissions FROM contact_form WHERE DATE(created_at) = DATE(cf.created_at)) as date_submissions
                                                    
                                                FROM 
                                                contact_form cf
                                                WHERE
                                                DATE(cf.created_At) BETWEEN '$start_date' AND '$end_date'
                                                AND
                                                cf.page_reference != 'direct-intake'
                                                ORDER BY DATE(created_at) ASC
                                            ");
        $intake_sub                     =   DB::select("
                                                SELECT 
                                                    status
                                                FROM 
                                                client_intake_form cif
                                                WHERE
                                                DATE(cif.submitted_at) BETWEEN '$start_date' AND '$end_date'
                                            ");
        $casefiles                      =   DB::select("
                                                SELECT 
                                                    intake_form_id,
                                                    client_id,
                                                    status
                                                FROM
                                                case_files caf
                                                WHERE
                                                DATE(caf.created_At) BETWEEN '$start_date' AND '$end_date'
                                            ");
        $records                        =   new stdClass();
        $get_pages                      =   DB::select("
                                                SELECT 
                                                    cf.page_reference
                                                FROM 
                                                contact_form cf
                                                WHERE
                                                cf.page_reference != 'direct-intake' AND cf.page_reference IS NOT NULL
                                                GROUP BY cf.page_reference
                                                ORDER BY DATE(created_at) ASC
                                            ");
        $pages_array                    =   collect($get_pages)->unique('page_reference')->pluck('page_reference')->toArray();
        $pages                          =   $pages_array;
        $all_pages                      =   [];
        foreach ($pages as $key => $page) {
            if($page){
                $page_name_conc         =   ucwords(str_replace('-',' ',$page));
                if($page != 'home' && $page != 'contact'){
                    $page_slug_conc     =   strtok($page, "?#");
                    if($page_slug_conc){
                        $page_slug      =   $page_slug_conc;
                    }else{
                        $page_slug      =   $page;
                    }
                    if($page == "?utm_source=google&utm_medium=organic&utm_campaign=local&utm_content=milton"){
                        $page_slug      =   'milton';
                    }
                    if($page_slug){
                        $page_name          =   DB::table('pagebuilder__page_translations')->selectRaw("title as page_name")->where('route','/'.$page_slug)->first();
                        if($page_name){
                            $page_name      =   $page_name->page_name;
                        }else{
                            $page_name      =   $page_name_conc;
                        }
                    }
                }else{
                    if($page == 'contact'){
                        $page_name      =   'Contact Us';
                    }
                    if($page == 'home'){
                        $page_name      =   'Home';
                    }
                }
                $current_page_inquiries =   DB::select("
                                                SELECT 
                                                    count(id) as ttl_pages,
                                                    cf.page_reference as page,
                                                    DATE(cf.created_at) as created_at 
                                                FROM 
                                                contact_form cf
                                                WHERE
                                                DATE(created_at) BETWEEN '$start_date' AND '$end_date'
                                                AND
                                                cf.page_reference != 'direct-intake'
                                                AND
                                                page_reference LIKE '%$page%'
                                                group by page
                                            ");
                if($current_page_inquiries && $current_page_inquiries[0]->page != null){
                    $currnt_page[]      =   [$page_name,$current_page_inquiries[0]->ttl_pages];
                }else{
                    $currnt_page[]      =   [$page_name,0];
                }
                $pre_page_inquiries     =    DB::select("
                                                SELECT 
                                                    count(id) as ttl_pages,
                                                    cf.page_reference as page,
                                                    DATE(cf.created_at) as created_at
                                                FROM 
                                                contact_form cf
                                                WHERE
                                                DATE(created_at) BETWEEN '$start_p_date' AND '$end_p_date'
                                                AND
                                                cf.page_reference != 'direct-intake'
                                                AND
                                                page_reference LIKE '%$page%'
                                                group by page
                                            ");
                if($pre_page_inquiries && $pre_page_inquiries[0]->page != null){
                    $previous_page[]    =   [$page_name,$pre_page_inquiries[0]->ttl_pages];
                }else{
                    $previous_page[]    =   [$page_name,0];
                }
                $all_pages[]            =   $page_name;
            }
        }
        $records->leads_pages           =   $all_pages;
        $records->current_pages_records =   $currnt_page;                     
        $records->previous_pages_records=   $previous_page;  
        $records->ttl_inquiries         =   collect($basic_records)->count();                 
        $records->ttl_web_inquiries     =   collect($basic_records)->WHERE('status',1)->count();
        $records->ttl_web_leads         =   collect($basic_records)->WHERE('status',2)->count();
        $records->ttl_spam_leads        =   collect($basic_records)->WHERE('status',3)->count();
        $records->ttl_deleted_leads     =   collect($basic_records)->WHERE('status',4)->count();
        $records->ttl_disqualified_leads=   collect($basic_records)->WHERE('status',7)->count();
        $records->ttl_deadleads_leads   =   collect($basic_records)->WHERE('status',8)->count();
        $records->ttl_intake_submissions=   collect($intake_sub)
                                            ->filter(function ($item) {
                                                return $item->status == 2 || $item->status == 4;
                                            })
                                            ->count();
        $records->ttl_intakes           =   collect($intake_sub)->count();
        $records->ttl_pending_casefiles =   collect($casefiles)->WHERE('status',1)->count();
        $records->ttl_casefiles         =   collect($casefiles)->unique('intake_form_id')->count();
        $records->daily_submissions     =   collect($basic_records)->unique('created_at')->toArray();
        $records->dates                 =   $dates;
        return response()->JSON([
            'status'    =>  'success',
            'data'      =>  $records
        ]);
    }
    public function dashboardCalenderRecords($date){
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

    function unique_multidim_array($array, $key) { 
        $temp_array = array(); 
        $i = 0; 
        $key_array = array(); 
        
        foreach($array as $val) { 
            if (!in_array($val[$key], $key_array)) { 
                $key_array[$i]  = $val[$key]; 
                $temp_array[$i] = $val; 
            } 
            $i++; 
        } 
        return $temp_array; 
    }
}
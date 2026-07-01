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
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait FileUploadInStorage{

    public function FileStore($file_real_path,$file_name,$file_mime_type,$upload_path){
        // $store_path                             =   explode('/',$upload_path);
        // $upload_path                            =   $store_path[0].'/'.$store_path[1].'/'.$store_path[2].'/'.$store_path[3];
        // $file_name                              =   $store_path[4];
        $path                                   =   storage_path('app/public/'.$upload_path);
        if(Storage::move($file_real_path, 'public/'.$upload_path.'/'. $file_name)){
            logger()->info("$file_name File store in $upload_path Successfully");
            // return $upload_path;
        }else{
            logger()->info("$file_name File store in $upload_path Failed'");
        };
    }
}
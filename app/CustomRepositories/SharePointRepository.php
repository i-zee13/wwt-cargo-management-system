<?php

namespace App\CustomRepositories;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class SharePointRepository
{
    protected $site_name;
    protected $tenant_name;
    protected $tenant_id;
    protected $client_id;
    protected $client_secret;
    protected $base_url;
    protected $base_directory;
    protected $access_token;
    public function __construct()
    {
        $this->getCredential();
        $this->view_url         =   "https://".$this->tenant_name.".sharepoint.com";
        $this->base_url         =   "https://".$this->tenant_name.".sharepoint.com/sites/".$this->site_name."/_api/web";
        $this->base_directory   =   "/sites/".$this->site_name."/Shared Documents";
        $this->access_token     =   $this->token();
    }

    public function UploadFile($objFile, $file_name, $file_directory, $syncDir=true)
    {
        $Result =   new \stdClass();
        try {
            if (!empty($objFile))
            {
                if ($syncDir == true)
                {
                    $check_directory    =   $this->SyncDirectories($file_directory);
                    if ($check_directory->status == 1)
                    {
                        $Result =   $this->Upload($objFile, $file_name, $file_directory);
                    }
                    else
                    {
                        $Result =   $check_directory;
                    }
                }
                else
                {
                    $Result =   $this->Upload($objFile, $file_name, $file_directory);
                }
            }
            else
            {
                $Result->status     =   0;
                $Result->message    =   'Error! Please file to upload on share point.';
            }
        }catch (\Exception $exception){
            $Result->status     =   0;
            $Result->message    =   $exception->getMessage();
        }
        return $Result;
    }

    protected function Upload($objFile, $file_name, $file_directory)
    {
        $Result =   new \stdClass();
        try {
            $upload_directory   =   $this->base_directory."/".$file_directory;
            $endpoint           =   $this->base_url."/GetFolderByServerRelativeUrl('".$upload_directory."')/Files/add(url='".$file_name."',overwrite=true)')";
            $file_content       =   file_get_contents($objFile->getRealPath());
            $headers            =   array(
                'Content-Length'    =>  strlen($file_content),
                'Accept'            =>  'application/json',
                'Content-Type'      =>  'application/json; odata=verbose',
                'Host'              =>  $this->tenant_name.'.sharepoint.com',
                'Authorization'     =>  'Bearer '.$this->access_token,
            );
            $client     =   new Client(['verify' => false, 'headers' => $headers]);
            $response   =   $client->request('POST', $endpoint, ['body' => $file_content]);
            $statusCode =   $response->getStatusCode();
            if ($statusCode == 200)
            {
                $Result->status     =   1;
                $Result->message    =   'Success';
                $Result->response   =   $this->view_url.''.$upload_directory;
            }
            else
            {
                $Result->status     =   0;
                $Result->message    =   'Error! Unable to upload file on share point. Please try again.';
            }
        }catch (\Exception $exception){
            $Result->status     =   0;
            $Result->message    =   $exception->getMessage();
        }
        return $Result;
    }

    protected function SyncDirectories($upload_directory)
    {
        $Result =   new \stdClass();
        try {
            $folders    =   !empty($upload_directory) ? explode('/', $upload_directory) : array();
            if (count($folders) > 0)
            {
                $previous_folders   =   '';
                foreach ($folders as $folder)
                {
                    $data   =   [
                        '__metadata'    =>  ['type' =>  'SP.Folder'],
                        'ServerRelativeUrl' =>  $this->base_directory."/".$previous_folders.$folder,
                    ];
                    $headers = [
                        'Content-Length' => strlen(json_encode($data)),
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json; odata=verbose',
                        'Host' => $this->tenant_name.'.sharepoint.com',
                        'Authorization' => 'Bearer '.$this->access_token,
                    ];
                    $endpoint   =   $this->base_url."/folders";
                    $client     =   new Client(['verify' => false, 'headers' => $headers,]);
                    $response   =   $client->request('POST', $endpoint, ['body' => json_encode($data)]);
                    $statusCode =   $response->getStatusCode();
                    if ($statusCode == 200 || $statusCode == 201)
                    {
                        $Result->status     =   1;
                        $Result->message    =   'Success';
                    }
                    else
                    {
                        $Result->status     =   0;
                        $Result->message    =   'Error! Unable to create directory. Please try again.';
                        return $Result;
                    }
                    $previous_folders   .=   $folder.'/';
                }
            }
            else
            {
                $Result->status     =   0;
                $Result->message    =   'Error! Please provide your folder directory to upload files on share point.';
            }
        }catch (\Exception $exception){
            $Result->status     =   0;
            $Result->message    =   $exception->getMessage();
        }
        return $Result;
    }
    public function DeleteFile($file_directory)
    {
        $Result             =   new \stdClass();
        try {
            $upload_directory   =   $this->base_directory."/".$file_directory;
            $endpoint           =   $this->base_url."/GetFileByServerRelativeUrl('".$upload_directory."')/recycle";
            $headers            =   array(
                'Accept'        =>  'application/json',
                'Host'          =>  $this->tenant_name.'.sharepoint.com',
                'Authorization' =>  'Bearer '.$this->access_token,
            );
            $client             =   new Client(['verify' => false,]);
            $response           =   $client->delete($endpoint,['headers' => $headers]);
            $statusCode         =   $response->getStatusCode();
            if ($statusCode == 200)
            {
                $Result->status     =   1;
                $Result->message    =   'Success';
            }
            else
            {
                $Result->status     =   0;
                $Result->message    =   'Error! Unable to delete file on share point. Please try again.';
            }
        }catch (\Exception $exception){
            $Result->status     =   0;
            $Result->message    =   $exception->getMessage();
        }
        return $Result;
    }

    protected function token()
    {
        $access_token   =   '';
        try {
            $endpoint       =   'https://accounts.accesscontrol.windows.net/'.$this->tenant_id.'/tokens/OAuth/2';
            $data['grant_type']     =   'client_credentials';
            $data['client_id']      =   $this->client_id.'@'.$this->tenant_id;
            $data['client_secret']  =   $this->client_secret;
            $data['resource']       =   '00000003-0000-0ff1-ce00-000000000000/'.$this->tenant_name.'.sharepoint.com@'.$this->tenant_id;
            $data['scope']          =   'https://'.$this->tenant_name.'.sharepoint.com/Sites.FullControl.All';
            $headers = [
                'Content-Length: '.strlen(json_encode($data)),
                'Content-Type: multipart/form-data',
                'Host: accounts.accesscontrol.windows.net',
            ];
            $client     =   new Client(['verify' => false ]);
            $response   =   $client->request('POST', $endpoint, ['form_params' => $data, 'headers' => $headers]);
            $statusCode =   $response->getStatusCode();
            if ($statusCode == 200)
            {
                $content        =   json_decode($response->getBody());
                $access_token   =   isset($content->access_token) ? $content->access_token : '';
            }
        }catch (\Exception $exception){
            //
        }
        return $access_token;
    }

    protected function getCredential()
    {
        $setting    =   array();
        $credential =   DB::table('integrations')->where('type', '=', 'mssharepoint')->where('status', '=', 'active')->first();
        if (isset($credential->setting) && $credential->setting != '')
        {
            $mode       =   isset($credential->mode) ? $credential->mode : '';
            $settings   =   json_decode($credential->setting, true);
            $setting    =   isset($settings[$mode]) ? $settings[$mode] : array();
        }
        $this->site_name        =   isset($setting['site_name']) ? $setting['site_name'] : '';
        $this->tenant_name      =   isset($setting['tenant_name']) ? $setting['tenant_name'] : '';
        $this->tenant_id        =   isset($setting['tenant_id']) ? $setting['tenant_id'] : '';
        $this->client_id        =   isset($setting['client_id']) ? $setting['client_id'] : '';
        $this->client_secret    =   isset($setting['client_secret']) ? $setting['client_secret'] : '';
    }
}
<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SharePointUploadSuccessful
{
    public $uploadResult;
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $file_name;
    public $upload_path;
    public $file_mime_type;
    public $case_file_id;
    public $file_complete;

    public function __construct($file_name, $upload_path, $file_mime_type,$case_file_id,$file_complete)
    {
        $this->file_name        =   $file_name;
        $this->upload_path      =   $upload_path;
        $this->file_mime_type   =   $file_mime_type;
        $this->case_file_id     =   $case_file_id;
        $this->file_complete    =   $file_complete;
    }
}

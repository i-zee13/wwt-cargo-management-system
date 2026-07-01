<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use DB;
class NotificationSending implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $message;
    public $inquiry_access;

    public function __construct($message,$inquiry_access)
    {
        $this->message          =   $message;
        if($inquiry_access){
            $this->inquiry_access   =   $inquiry_access;
        }else{
            $inquiry_access         =   DB::table('user_subscribed_notifications')->get();
            $this->inquiry_access   =   $inquiry_access;
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return collect($this->inquiry_access)->map(function ($inquiry_access) {
            return 'user.'.$inquiry_access->user_id;
        })->all();   
        // return ['my-channel'];
    }

    public function broadcastAs()
    {
        return 'my-event';
    }
}

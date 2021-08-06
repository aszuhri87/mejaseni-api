<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function broadcastAs()
    {
        return 'admin.notification';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('admin-notification');
    }

    public function broadcastWith()
    {
        $this->email_notification($this->data);

        return [
            'data' => $this->data
        ];
    }

    public function email_notification($notification, $id = null)
    {
        try {
            Mail::send('mail.notification', compact('notification'), function($message){
                $message->to('mtaufiikh@gmail.com', 'Admin Mejaseni')
                    ->from('info@mejaseni.com', 'MEJASENI')
                    ->subject('Mejaseni Notification');
            });
        } catch (\Exception $th) {
            return false;
        }

        return true;
    }
}

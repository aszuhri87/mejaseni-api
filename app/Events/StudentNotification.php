<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class StudentNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $id;
    public $subject;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data, $id, $subject = 'Mejaseni Notification')
    {
        $this->data = $data;
        $this->id = $id;
        $this->subject = $subject;
    }

    public function broadcastAs()
    {
        return 'student.notification.'.$this->id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('student-notification');
    }

    public function broadcastWith()
    {
        $this->email_notification($this->data, $this->id);

        return [
            'id' => $this->id,
            'data' => $this->data
        ];
    }

    public function email_notification($notification, $id = null)
    {
        $user = \DB::table('students')
            ->select([
                'email',
                'name'
            ])
            ->where('id', $id)
            ->first();

        try {
            if(config('app.env') == 'production') {
                Mail::send('mail.notification', compact('notification'), function($message) use($user){
                    $message->to($user->email, $user->name)
                        ->from('info@mejaseni.com', 'MEJASENI')
                        ->subject($this->subject);
                });
            }
        } catch (\Exception $th) {
            return false;
        }

        return true;
    }
}

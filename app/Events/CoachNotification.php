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

class CoachNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data, $id)
    {
        $this->data = $data;
        $this->id = $id;
    }

    public function broadcastAs()
    {
        return 'coach.notification.'.$this->id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('coach-notification');
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
        $user = \DB::table('coaches')
            ->select([
                'email',
                'name'
            ])
            ->where('id', $id)
            ->first();

        $is_coach = true;

        try {
            Mail::send('mail.notification', compact('notification', 'is_coach'), function($message) use($user){
                $message->to($user->email, $user->name)
                    ->from('info@mejaseni.com', 'MEJASENI')
                    ->subject('Mejaseni Notification');
            });
        } catch (\Exception $th) {
            return false;
        }

        return true;
    }
}

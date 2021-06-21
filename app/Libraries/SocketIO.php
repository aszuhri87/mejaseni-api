<?php

namespace App\Libraries;

use Http;

class SocketIO
{
    public static function message($user_id = 'public', $channel_id, $data)
    {
        $response = Http::withHeaders([
            'token' => config('socket.token'),
            'user-id' => $user_id
        ])
        ->post('https://client.socket.var-x.id/message', [
            'channel_id' => $channel_id,
            'data' => $data
        ]);
    }
}

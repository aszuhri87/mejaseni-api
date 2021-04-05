<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\TemporaryMedia;

use Storage;

class MediaController extends Controller
{
    public function file_upload(Request $request)
    {
        set_time_limit(600000);

        try {
            $path = Storage::disk('s3')->put('media', $request->file);

            $url = Storage::disk('s3')->url('/');

            $temp = TemporaryMedia::create([
                'path' => $path
            ]);

            return response([
                "status" => 200,
                "data" => $temp,
                "url" => $url.''.$path,
                "message"   => 'Successfully saved!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function file_delete($id)
    {
        try {
            $result = TemporaryMedia::find($id);

            DB::transaction(function () use($result){
                Storage::disk('s3')->delete($result->path);
                $result->delete();
            });

            return response([
                "message"   => 'Successfully deleted!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }
}

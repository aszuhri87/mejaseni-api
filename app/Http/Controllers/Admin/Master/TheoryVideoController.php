<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

use App\Models\TheoryVideo;
use App\Models\TheoryVideoFile;
use App\Models\TheoryVideoResolution;

use DataTables;
use Storage;
use Http;
use Log;

class TheoryVideoController extends BaseMenu
{
    public function dt($id)
    {
        $path = Storage::disk('s3')->url('/');

        $data = DB::table('theory_videos')
            ->select([
                'theory_videos.*',
                //DB::raw("CONCAT('{$path}',url) as file_url"),
            ])
            ->whereNull("theory_videos.deleted_at")
            ->where('theory_videos.session_video_id', $id)
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        try {

            $is_exist = TheoryVideo::whereNull('deleted_at')
                ->where([
                    'session_video_id' => $request->session_video_id,
                    'number' => $request->number
                ])->first();

            if($is_exist){
                return response([
                    "message"=> "Nomor Video Sudah Digunakan",
                ], 400);
            }

            $result = DB::transaction(function () use($request){
                $result = TheoryVideo::create([
                    'session_video_id' => $request->session_video_id,
                    'name' => $request->name,
                    'number' => $request->number,
                    'is_youtube' => isset($request->is_youtube) ? true : false,
                    'youtube_url' => isset($request->is_youtube) ? $request->url : null,
                    'is_public' => isset($request->is_public) ? true : false,
                ]);

                if(!isset($request->is_youtube)){
                    $path = Storage::disk('s3')->url('/');
                    $video_converter_hook = config('app.url')."/api/video-converter/".$result->id."/hook";
                    $url = $path . $request->file;

                    $client = new Client([
                        'base_uri' => config('app.video_converter_host'),
                    ]);

                    $response = $client->request('POST', '/video',[
                        "multipart" => [
                            [
                                "name" => "video",
                                "contents" => file_get_contents($url),
                                "filename" => $url
                            ]
                        ],
                        "query" => [
                            "video_converter_hook" => $video_converter_hook
                        ],
                    ]);

                    $status_created = 201;
                    if($response->getStatusCode() == $status_created){
                        return $result;
                    }

                    return response([
                        "message"=> "Internal Server Error",
                    ], $response->status());
                }

            });

            return response([
                "data"      => $result,
                "message"   => 'Successfully saved!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $result = TheoryVideo::find($id);

            $is_exist = TheoryVideo::whereNull('deleted_at')
                ->where([
                    'session_video_id' => $request->session_video_id,
                    'number' => $request->number
                ])->first();

            if($is_exist && $result->number != $request->number){
                return response([
                    "message"=> "Nomor Video Sudah Digunakan",
                ], 400);
            }

            $result = DB::transaction(function () use($request, $id){
                $result = TheoryVideo::find($id);

                $is_exist = TheoryVideo::whereNull('deleted_at')
                    ->where([
                        'session_video_id' => $request->session_video_id,
                        'number' => $request->number
                    ])->first();

                if($is_exist){
                    return response([
                        "message"=> "Nomor Video Sudah Digunakan",
                    ], 400);
                }

                $data = [
                        'name' => $request->name,
                        'number' => $request->number,
                        'is_youtube' => isset($request->is_youtube) ? true : false,
                        'youtube_url' => isset($request->is_youtube) ? $request->url : null,
                        'is_public' => isset($request->is_public) ? true : false,
                ];

                if(!isset($request->is_youtube)){
                    if(isset($request->url)){
                        $path = Storage::disk('s3')->url('/');
                        $video_converter_hook = config('app.url')."/api/video-converter/".$result->id."/hook";
                        $url = $path . $request->file;

                        $client = new Client([
                            'base_uri' => config('app.video_converter_host'),
                        ]);

                        $response = $client->request('POST', '/video',[
                            "multipart" => [
                                [
                                    "name" => "video",
                                    "contents" => file_get_contents($url),
                                    "filename" => $url
                                ]
                            ],
                            "query" => [
                                "video_converter_hook" => $video_converter_hook
                            ],
                        ]);

                        $status_created = 201;
                        if($response->getStatusCode() == $status_created){
                            $data['is_youtube'] = false;
                            $data['is_converter_complete'] = false;
                            $data['video_url'] = '';
                        }else{
                            return response([
                                "message"=> "Internal Server Error",
                            ], $response->status());
                        }

                    }
                }

                $result->update($data);

                return $result;
            });

            return response([
                "data"      => $result,
                "message"   => 'Successfully updated!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $result = TheoryVideo::find($id);

            DB::transaction(function () use($result){
                $result->delete();
            });

            if ($result->trashed()) {
                return response([
                    "message"   => 'Successfully deleted!'
                ], 200);
            }
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function file_dt($id)
    {
        $path = Storage::disk('s3')->url('/');

        $data = DB::table('theory_video_files')
            ->select([
                'theory_video_files.*',
                DB::raw("CONCAT('{$path}',url) as file_url"),
            ])
            ->whereNull("theory_video_files.deleted_at")
            ->where('theory_video_files.session_video_id', $id)
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function file_store(Request $request)
    {
        try {
            $result = DB::transaction(function () use($request){
                $result = TheoryVideoFile::create([
                    'session_video_id' => $request->session_video_id,
                    'name' => $request->name,
                    'url' => $request->file,
                    'description' => $request->description,
                ]);

                return $result;
            });

            return response([
                "data"      => $result,
                "message"   => 'Successfully saved!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function file_update(Request $request, $id)
    {
        try {
            $result = DB::transaction(function () use($request, $id){
                $result = TheoryVideoFile::find($id)->update([
                    'session_video_id' => $request->session_video_id,
                    'name' => $request->name,
                    'url' => $request->file,
                    'description' => $request->description,
                ]);

                return $result;
            });

            return response([
                "data"      => $result,
                "message"   => 'Successfully updated!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function file_destroy($id)
    {
        try {
            $result = TheoryVideoFile::find($id);

            DB::transaction(function () use($result){
                $result->delete();
            });

            if ($result->trashed()) {
                return response([
                    "message"   => 'Successfully deleted!'
                ], 200);
            }
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }


    public function video_converter_hook(Request $request, $id)
    {
        try {
            $result = DB::transaction(function () use($request, $id){

                TheoryVideoResolution::where('theory_video_id',$id)->delete();
                foreach ($request->resolutions as $key => $resolution) {
                    $video_resolution = TheoryVideoResolution::updateOrCreate([
                        'theory_video_id' => $id,
                        'name' => $resolution['resolution'],
                        'url' => $resolution['url']
                    ]);
                }


                $result = TheoryVideo::find($id)->update([
                    'duration' => $request->duration,
                    'video_url' => $request->video_url,
                    'is_converter_complete' => true
                ]);

                return $result;
            });

            return response([
                "data"      => $result,
                "message"   => 'Successfully updated!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }
}

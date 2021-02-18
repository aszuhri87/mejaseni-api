<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\TheoryVideo;

use DataTables;
use Storage;

class TheoryVideoController extends BaseMenu
{
    public function dt($id)
    {
        $path = Storage::disk('s3')->url('/');

        $data = DB::table('theory_videos')
            ->select([
                'theory_videos.*',
                DB::raw("CONCAT('{$path}',url) as file_url"),
            ])
            ->whereNull("theory_videos.deleted_at")
            ->where('theory_videos.session_video_id', $id)
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        try {
            $result = DB::transaction(function () use($request){
                $result = TheoryVideo::create([
                    'session_video_id' => $request->session_video_id,
                    'name' => $request->name,
                    'is_youtube' => isset($request->is_youtube) ? true : false,
                    'url' => isset($request->is_youtube) ? $request->url : $request->file,
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

    public function update(Request $request, $id)
    {
        try {
            $result = DB::transaction(function () use($request, $id){
                $result = TheoryVideo::find($id)->update([
                    'session_video_id' => $request->session_video_id,
                    'name' => $request->name,
                    'is_youtube' => isset($request->is_youtube) ? true : false,
                    'url' => isset($request->is_youtube) ? $request->url : $request->file,
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
}

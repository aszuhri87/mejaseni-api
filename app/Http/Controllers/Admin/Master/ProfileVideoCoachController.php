<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\ProfileCoachVideo;

use DataTables;
use Storage;

class ProfileVideoCoachController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Master'
            ],
            [
                'title' => 'Profile Video Coach'
            ],
        ];

        return view('admin.master.profile-video-coach.index', [
            'title' => 'Profile Video Coach',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $path = Storage::disk('s3')->url('/');

        $data = DB::table('profile_coach_videos')
            ->select([
                'profile_coach_videos.*',
                'coaches.name',
                DB::raw("CASE
                    WHEN is_youtube = true
                    THEN profile_coach_videos.url
                    ELSE CONCAT('{$path}',profile_coach_videos.url)
                END url_video")
            ])
            ->leftJoin('coaches','coaches.id','=','profile_coach_videos.coach_id')
            ->whereNull("profile_coach_videos.deleted_at")
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        try {
            $result = DB::transaction(function () use($request){
                $result = ProfileCoachVideo::create([
                    'coach_id' => $request->coach_id,
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
                $result = ProfileCoachVideo::find($id)->update([
                    'coach_id' => $request->coach_id,
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
            $result = ProfileCoachVideo::find($id);

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

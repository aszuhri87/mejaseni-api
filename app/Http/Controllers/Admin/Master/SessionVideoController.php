<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\SessionVideo;

use DataTables;
use Storage;

class SessionVideoController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Master'
            ],
            [
                'title' => 'Courses'
            ],
            [
                'title' => 'Video'
            ],
        ];

        return view('admin.master.session-video.index', [
            'title' => 'Video',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $path = Storage::disk('s3')->url('/');
        $theories = DB::table('theory_videos')
            ->select([
                'theory_videos.session_video_id',
                DB::raw("COUNT(theory_videos.id) as count_theory")
            ])
            ->groupBy("theory_videos.session_video_id")
            ->whereNull("theory_videos.deleted_at");

        $data = DB::table('session_videos')
            ->select([
                'session_videos.*',
                'sub_classroom_categories.classroom_category_id',
                'coaches.name as coach',
                'expertises.name as expertise',
                'sub_classroom_categories.name as sub_category',
                'classroom_categories.name as category',
                DB::raw("CONCAT('{$path}',session_videos.image) as image_url"),
                DB::raw("CASE
                    WHEN theories.count_theory IS NULL THEN 0
                    ELSE theories.count_theory
                END count_theory")
            ])
            ->leftJoin('coaches', 'coaches.id','=','session_videos.coach_id')
            ->leftJoin('expertises', 'expertises.id','=','session_videos.expertise_id')
            ->leftJoin('sub_classroom_categories', 'sub_classroom_categories.id','=','session_videos.sub_classroom_category_id')
            ->leftJoin('classroom_categories', 'classroom_categories.id','=','sub_classroom_categories.classroom_category_id')
            ->leftJoinSub($theories, 'theories', function($join){
                $join->on("theories.session_video_id", "session_videos.id");
            })
            ->whereNull([
                'session_videos.deleted_at'
            ])
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        try {
            if(!isset($request->image)){
                return response([
                    "message"   => 'Gambar harus diisi!'
                ], 400);
            }

            if(!isset($request->sub_classroom_category_id)){
                return response([
                    "message"   => 'Sub Kategori harus diisi!'
                ], 400);
            }

            $result = DB::transaction(function () use($request){
                if(isset($request->image)){
                    $file = $request->file('image');
                    $path = Storage::disk('s3')->put('media', $file);
                }

                $result = SessionVideo::create([
                    'sub_classroom_category_id' => $request->sub_classroom_category_id,
                    'expertise_id' => $request->expertise_id,
                    'coach_id' => $request->coach_id,
                    'description' => $request->description,
                    'price' => $request->price,
                    'name' => $request->name,
                    'datetime' => date('Y-m-d H:i:s'),
                    'image' => $path
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
            if(!isset($request->sub_classroom_category_id)){
                return response([
                    "message"   => 'Sub Kategori harus diisi!'
                ], 400);
            }

            $result = DB::transaction(function () use($request, $id){
                $path = null;

                if(isset($request->image)){
                    $file = $request->file('image');
                    $path = Storage::disk('s3')->put('media', $file);
                }

                $result = SessionVideo::find($id)->update([
                    'sub_classroom_category_id' => $request->sub_classroom_category_id,
                    'expertise_id' => $request->expertise_id,
                    'coach_id' => $request->coach_id,
                    'description' => $request->description,
                    'price' => $request->price,
                    'name' => $request->name,
                    'image' => $path ? $path:$result->image
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

    public function show($id)
    {
        $video = SessionVideo::find($id);

        if(!$video){
            return Redirect::back()->withErrors(['message' => 'Data tidak ditemukan']);
        }

        $navigation = [
            [
                'title' => 'Master'
            ],
            [
                'title' => 'Courses'
            ],
            [
                'title' => 'Video'
            ],
            [
                'title' => $video->name
            ],
        ];

        return view('admin.master.session-video-detail.index', [
            'title' => $video->name,
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
            'data' => $video
        ]);
    }

    public function destroy($id)
    {
        try {
            $result = SessionVideo::find($id);

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

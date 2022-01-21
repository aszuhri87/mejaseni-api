<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\MasterLesson;
use App\Models\GuestStarMasterLesson;

use DataTables;
use Storage;

class MasterLessonController extends BaseMenu
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
                'title' => 'Master Lesson'
            ],
        ];

        return view('admin.master.master-lesson.index', [
            'title' => 'Master Lesson',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $path = Storage::disk('s3')->url('/');

        $data = DB::table('master_lessons')
            ->select([
                'master_lessons.*',
                'classroom_categories.name as category',
                'sub_classroom_categories.name as sub_category',
                DB::raw("CONCAT('{$path}',poster) as image_url"),
            ])
            ->leftJoin('classroom_categories','classroom_categories.id','=','master_lessons.classroom_category_id')
            ->leftJoin('sub_classroom_categories','sub_classroom_categories.id','=','master_lessons.sub_classroom_category_id')
            ->whereNull([
                'master_lessons.deleted_at'
            ])
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        try {
            if(!isset($request->file)){
                return response([
                    "message"   => 'Poster harus diisi!'
                ], 400);
            }

            if(!isset($request->guests)){
                return response([
                    "message"   => 'Guest Star harus diisi!'
                ], 400);
            }

            $sub_category_check = DB::table('sub_classroom_categories')
                ->select([
                    'id',
                    'name'
                ])
                ->where('classroom_category_id', $request->classroom_category_id)
                ->whereNull('deleted_at')
                ->count();

            if($sub_category_check > 0 && !isset($request->sub_classroom_category_id)){
                return response([
                    "message"   => 'Sub Kategori harus diisi!'
                ], 400);
            }

            $result = DB::transaction(function () use($request){
                $result = MasterLesson::create([
                    'classroom_category_id' => $request->classroom_category_id,
                    'sub_classroom_category_id' => $request->sub_classroom_category_id,
                    'price' => $request->price,
                    'datetime' => date('Y-m-d H:i:s', strtotime($request->date.' '.$request->time)),
                    'platform_id' => $request->platform_id,
                    'name' => $request->name,
                    'poster' => $request->file,
                    'slot' => $request->slot,
                    'platform_link' => $request->platform_link,
                    'description' => $request->description,
                    'buy_btn_disable' => isset($request->buy_btn_disable) ? true : false,
                    'hide' => isset($request->hide) ? true : false,
                ]);

                if(isset($request->guests) && count($request->guests) > 0){
                    foreach ($request->guests as $key => $value) {
                        GuestStarMasterLesson::create([
                            'master_lesson_id' => $result->id,
                            'guest_star_id' => $value,
                        ]);
                    }
                }

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
                $master_lesson = MasterLesson::find($id);

                if(isset($request->file)){
                    $master_lesson->poster = $request->file;
                    $master_lesson->update();
                }

                $master_lesson->update([
                    'classroom_category_id' => $request->classroom_category_id,
                    'sub_classroom_category_id' => $request->sub_classroom_category_id,
                    'price' => $request->price,
                    'datetime' => date('Y-m-d H:i:s', strtotime($request->date.' '.$request->time)),
                    'platform_id' => $request->platform_id,
                    'name' => $request->name,
                    'slot' => $request->slot,
                    'platform_link' => $request->platform_link,
                    'description' => $request->description,
                    'buy_btn_disable' => isset($request->buy_btn_disable) ? true : false,
                    'hide' => isset($request->hide) ? true : false,
                ]);

                if(isset($request->guests) && count($request->guests) > 0){
                    foreach ($request->guests as $key => $value) {
                        GuestStarMasterLesson::create([
                            'master_lesson_id' => $master_lesson->id,
                            'guest_star_id' => $value,
                        ]);
                    }
                }

                return $master_lesson;
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
            $result = MasterLesson::find($id);

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

    public function get_guest_star($id)
    {
        try {
            $result = GuestStarMasterLesson::select([
                    'guest_star_master_lessons.id',
                    'guest_star_master_lessons.master_lesson_id',
                    'guest_stars.name'
                ])
                ->leftJoin('guest_stars', 'guest_stars.id','=','guest_star_master_lessons.guest_star_id')
                ->where('master_lesson_id', $id)
                ->get();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function destroy_guest_star($id)
    {
        try {
            $result = GuestStarMasterLesson::find($id)->delete();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }
}

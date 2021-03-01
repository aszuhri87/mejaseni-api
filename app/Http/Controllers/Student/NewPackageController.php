<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Storage;

class NewPackageController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Data Buy New Package'
            ],
        ];

        return view('student.new-package.index', [
            'title' => 'Buy New Package',
            'navigation' => $navigation,
            'list_menu' => $this->menu_student(),
        ]);
    }

    public function get_package()
    {
        try {
            $path = Storage::disk('s3')->url('/');
            $result = DB::table('classrooms')
                ->select([
                    'classrooms.id as classroom_id',
                    'classrooms.name as classroom_name',
                    'classrooms.description',
                    'classrooms.image',
                    'classrooms.price',
                    'classrooms.session_total',
                    'classrooms.session_duration',
                    DB::raw("CONCAT('{$path}',classrooms.image) as image_url"),
                ])
                ->where('classrooms.deleted_at')
                ->limit(6)
                ->get();

            foreach ($result as $key => $value) {
                $tools = DB::table('classroom_tools')
                    ->select([
                        'tools.text as tool_name'
                    ])
                    ->where('classroom_id',$value->classroom_id)
                    ->leftJoin('tools','classroom_tools.tool_id','=','tools.id')
                    ->get();

                $coach = DB::table('coach_classrooms')
                    ->select([
                        'coaches.name as coach_name',
                        'coaches.id as coach_id',
                        DB::raw("CONCAT('{$path}',coaches.image) as coach_image_url"),
                    ])
                    ->leftJoin('coaches','coach_classrooms.coach_id','=','coaches.id')
                    ->where('classroom_id',$value->classroom_id)
                    ->whereNull([
                        'coach_classrooms.deleted_at',
                        'coaches.deleted_at',
                    ])
                    ->get();

                $value->tools = $tools;
                $value->coach = $coach;
            }

            return response([
                "status"    => 200,
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

    public function get_classroom_by_sub_category_id($sub_classroom_category_id)
    {
        try {
            $path = Storage::disk('s3')->url('/');
            $result = DB::table('classrooms')
                ->select([
                    'classrooms.id as classroom_id',
                    'classrooms.name as classroom_name',
                    'classrooms.description',
                    'classrooms.image',
                    'classrooms.price',
                    'classrooms.sub_classroom_category_id',
                    'classrooms.session_total',
                    'classrooms.session_duration',
                    DB::raw("CONCAT('{$path}',classrooms.image) as image_url"),
                ])
                ->where('classrooms.deleted_at')
                ->where('classrooms.sub_classroom_category_id',$sub_classroom_category_id)
                ->get();

            foreach ($result as $key => $value) {
                $tools = DB::table('classroom_tools')
                    ->select([
                        'tools.text as tool_name'
                    ])
                    ->where('classroom_id',$value->classroom_id)
                    ->leftJoin('tools','classroom_tools.tool_id','=','tools.id')
                    ->get();

                $coach = DB::table('coach_classrooms')
                    ->select([
                        'coaches.name as coach_name',
                        'coaches.id as coach_id',
                        DB::raw("CONCAT('{$path}',coaches.image) as coach_image_url"),
                    ])
                    ->leftJoin('coaches','coach_classrooms.coach_id','=','coaches.id')
                    ->where('classroom_id',$value->classroom_id)
                    ->whereNull([
                        'coach_classrooms.deleted_at',
                        'coaches.deleted_at',
                    ])
                    ->get();

                $value->tools = $tools;
                $value->coach = $coach;
            }

            return response([
                "status"    => 200,
                "data"      => $result,
                "message"   => 'OK!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_session_video(Request $request)
    {
        try {
            $path = Storage::disk('s3')->url('/');
            $expertise = DB::table('expertises')
                ->select([
                    'expertises.id',
                    'expertises.name',
                ])
                ->whereNull('expertises.deleted_at');

            $coach = DB::table('coaches')
                ->select([
                    'coaches.id',
                    'coaches.name',
                ])
                ->whereNull('coaches.deleted_at');

            $sub_classroom_category = DB::table('sub_classroom_categories')
                ->select([
                    'sub_classroom_categories.id',
                    'sub_classroom_categories.image',
                ])
                ->whereNull('sub_classroom_categories.deleted_at');

            $result = DB::table('session_videos')
                ->select([
                    'session_videos.id',
                    'session_videos.sub_classroom_category_id',
                    'session_videos.name',
                    'session_videos.datetime',
                    'session_videos.description',
                    'session_videos.price',
                    'session_videos.coach_id',
                    'session_videos.expertise_id',
                    DB::raw("CONCAT('{$path}',sub_classroom_categories.image) as image_url"),
                    'coaches.name as coach_name',
                    'expertises.name as expertise_name',
                ])
                ->joinSub($sub_classroom_category, 'sub_classroom_categories', function ($join) {
                    $join->on('session_videos.sub_classroom_category_id', '=', 'sub_classroom_categories.id');
                })
                ->joinSub($coach, 'coaches', function ($join) {
                    $join->on('session_videos.coach_id', '=', 'coaches.id');
                })
                ->joinSub($expertise, 'expertises', function ($join) {
                    $join->on('session_videos.expertise_id', '=', 'expertises.id');
                })
                ->where(function($query) use($request){
                    if(!empty($request->sub_classroom_category_id)){
                        $query->where('session_videos.sub_classroom_category_id',$request->sub_classroom_category_id);
                    }
                })
                ->whereNull('session_videos.deleted_at')
                ->get();

            return response([
                "status" => 200,
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

    public function get_sub_classroom_category(Request $request)
    {
        try {
            $result = DB::table('sub_classroom_categories')
                ->select([
                    'id',
                    'name'
                ])
                ->where(function($query) use($request){
                    if(!empty($request->classroom_category_id)){
                        $query->where('classroom_category_id',$request->classroom_category_id);
                    }
                })
                ->whereNull('deleted_at')
                ->get();

            return response([
                "status" => 200,
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

    public function get_classroom_by_category_id($id)
    {
        try {
            $path = Storage::disk('s3')->url('/');
            $result = DB::table('classrooms')
                ->select([
                    'classrooms.id as classroom_id',
                    'classrooms.name as classroom_name',
                    'classrooms.description',
                    'classrooms.classroom_category_id',
                    'classrooms.image',
                    'classrooms.price',
                    'classrooms.sub_classroom_category_id',
                    'classrooms.session_total',
                    'classrooms.session_duration',
                    DB::raw("CONCAT('{$path}',classrooms.image) as image_url"),
                ])
                ->where('classrooms.deleted_at')
                ->where('classrooms.classroom_category_id',$id)
                ->get();

            foreach ($result as $key => $value) {
                $tools = DB::table('classroom_tools')
                    ->select([
                        'tools.text as tool_name'
                    ])
                    ->where('classroom_id',$value->classroom_id)
                    ->leftJoin('tools','classroom_tools.tool_id','=','tools.id')
                    ->get();

                $coach = DB::table('coach_classrooms')
                    ->select([
                        'coaches.name as coach_name',
                        'coaches.id as coach_id',
                        DB::raw("CONCAT('{$path}',coaches.image) as coach_image_url"),
                    ])
                    ->leftJoin('coaches','coach_classrooms.coach_id','=','coaches.id')
                    ->where('classroom_id',$value->classroom_id)
                    ->whereNull([
                        'coach_classrooms.deleted_at',
                        'coaches.deleted_at',
                    ])
                    ->get();

                $value->tools = $tools;
                $value->coach = $coach;
            }

            return response([
                "status"    => 200,
                "data"      => $result,
                "message"   => 'OK!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }
}

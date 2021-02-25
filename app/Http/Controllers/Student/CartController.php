<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Chart;

use Storage;

class CartController extends Controller
{
    public function store(Request $request)
    {
        try {
            $result = DB::transaction(function () use($request){
                $data = [];
                if($request->type == 1){
                    $data = [
                        'student_id' => $request->student_id,
                        'classroom_id' => $request->id
                    ];
                }elseif($request->type == 2){
                    $data = [
                        'student_id' => $request->student_id,
                        'master_lesson_id' => $request->id
                    ];
                }elseif($request->type == 3){
                    $data = [
                        'student_id' => $request->student_id,
                        'theory_id' => $request->id
                    ];
                }else{
                    $data = [
                        'student_id' => $request->student_id,
                        'session_video_id' => $request->id
                    ];
                }

                $cart = Chart::create($data);
                return $cart;
            });

            return response([
                "status"    => 200,
                "data"      => $result,
                "message"   => 'Successfully Add To Cart!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_cart($student_id)
    {
        try {
            $result = DB::transaction(function () use($student_id){
                $path = Storage::disk('s3')->url('/');
                $master_lesson = DB::table('master_lessons')
                    ->select([
                        'master_lessons.*',
                    ]);

                $session_video = DB::table('session_videos')
                    ->select([
                        'session_videos.*',
                    ]);

                $classroom = DB::table('classrooms')
                    ->select([
                        'classrooms.id',
                        'classrooms.package_type',
                        'classrooms.name',
                        'classrooms.price',
                        'classrooms.image',
                    ]);

                $theory = DB::table('theories')
                    ->select([
                        'theories.*',
                    ]);

                $cart = DB::table('charts')
                    ->select([
                        'charts.id',
                        'charts.master_lesson_id',
                        'charts.classroom_id',
                        'charts.session_video_id',
                        'charts.theory_id',
                        'classrooms.package_type',
                        'classrooms.name as classroom_name',
                        'classrooms.price as classroom_price',
                        DB::raw("CONCAT('{$path}',classrooms.image) as image_classroom"),
                        'session_videos.name as session_video_name',
                        'session_videos.price as session_video_price',
                        'theories.name as theory_name',
                        'theories.price as theory_price',
                    ])
                    ->leftJoinSub($master_lesson, 'master_lessons', function ($join) {
                        $join->on('charts.master_lesson_id', '=', 'master_lessons.id');
                    })
                    ->leftJoinSub($session_video, 'session_videos', function ($join) {
                        $join->on('charts.session_video_id', '=', 'session_videos.id');
                    })
                    ->leftJoinSub($classroom, 'classrooms', function ($join) {
                        $join->on('charts.classroom_id', '=', 'classrooms.id');
                    })
                    ->leftJoinSub($theory, 'theories', function ($join) {
                        $join->on('charts.theory_id', '=', 'theories.id');
                    })
                    ->where('charts.student_id',$student_id)
                    ->whereNull('charts.deleted_at')
                    ->get();
                return $cart;
            });

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
    public function delete_cart($cart_id)
    {
        try {
            $result = DB::transaction(function () use($cart_id){
                $delete = Chart::find($cart_id)->delete();
                return $delete;
            });

            return response([
                "status"    => 200,
                "data"      => $result,
                "message"   => 'Successfully Delete Cart!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }
}

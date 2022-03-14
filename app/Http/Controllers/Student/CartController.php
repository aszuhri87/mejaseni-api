<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class CartController extends Controller
{
    public function store(Request $request)
    {
        try {
            $result = DB::transaction(function () use ($request) {
                $data = [];
                if ($request->type == 1) {
                    $data = [
                        'student_id' => $request->student_id,
                        'classroom_id' => $request->id,
                    ];
                } elseif ($request->type == 2) {
                    $data = [
                        'student_id' => $request->student_id,
                        'master_lesson_id' => $request->id,
                    ];
                } elseif ($request->type == 3) {
                    $data = [
                        'student_id' => $request->student_id,
                        'theory_id' => $request->id,
                    ];
                } else {
                    $data = [
                        'student_id' => $request->student_id,
                        'session_video_id' => $request->id,
                    ];
                }

                if ($request->has('lat')) {
                    $data['lat'] = $request->lat;
                }
                if ($request->has('lng')) {
                    $data['lng'] = $request->lng;
                }

                $cart = Cart::create($data);

                return $cart;
            });

            return response([
                'status' => 200,
                'data' => $result,
                'message' => 'Successfully Add To Cart!',
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);

            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function get_cart($student_id)
    {
        try {
            $result = DB::transaction(function () use ($student_id) {
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

                $cart = DB::table('carts')
                    ->select([
                        'carts.id',
                        'carts.master_lesson_id',
                        'carts.classroom_id',
                        'carts.session_video_id',
                        'carts.theory_id',
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
                        $join->on('carts.master_lesson_id', '=', 'master_lessons.id');
                    })
                    ->leftJoinSub($session_video, 'session_videos', function ($join) {
                        $join->on('carts.session_video_id', '=', 'session_videos.id');
                    })
                    ->leftJoinSub($classroom, 'classrooms', function ($join) {
                        $join->on('carts.classroom_id', '=', 'classrooms.id');
                    })
                    ->leftJoinSub($theory, 'theories', function ($join) {
                        $join->on('carts.theory_id', '=', 'theories.id');
                    })
                    ->where('carts.student_id', $student_id)
                    ->whereNull('carts.deleted_at')
                    ->get();

                return $cart;
            });

            return response([
                'status' => 200,
                'data' => $result,
                'message' => 'OK!',
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);

            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function delete_cart($cart_id)
    {
        try {
            $result = DB::transaction(function () use ($cart_id) {
                $delete = Cart::find($cart_id)->delete();

                return $delete;
            });

            return response([
                'status' => 200,
                'data' => $result,
                'message' => 'Successfully Delete Cart!',
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);

            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function event(Request $request, $event_id)
    {
        try {
            $result = DB::transaction(function () use ($request, $event_id) {
                $user = Auth::guard('student')->user();

                $cart = Cart::create([
                    'event_id' => $event_id,
                    'student_id' => $user->id,
                ]);

                return $cart;
            });

            return response([
                'status' => 200,
                'data' => $result,
                'message' => 'Successfully Add To Cart!',
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);

            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function video_course(Request $request, $video_course_id)
    {
        try {
            $result = DB::transaction(function () use ($request, $video_course_id) {
                $user = Auth::guard('student')->user();

                $cart = Cart::create([
                    'session_video_id' => $video_course_id,
                    'student_id' => $user->id,
                ]);

                return $cart;
            });

            return response([
                'status' => 200,
                'data' => $result,
                'message' => 'Successfully Add To Cart!',
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);

            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }
}

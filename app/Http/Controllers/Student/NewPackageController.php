<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Storage;
use Auth;

use App\Models\GuestStarMasterLesson;
session_start();

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

    public function get_package(Request $request)
    {
        try {
            $path = Storage::disk('s3')->url('/');

            $transaction_detail = DB::table('transaction_details')
                ->select([
                    'transaction_details.cart_id',
                    'transaction_details.id',
                ])
                ->whereNull('transaction_details.deleted_at');

            $cart = DB::table('carts')
                ->select([
                    'carts.classroom_id',
                    'transaction_details.id as transaction_detail_id',
                ])
                ->leftJoinSub($transaction_detail, 'transaction_details', function ($join) {
                    $join->on('carts.id', '=', 'transaction_details.cart_id');
                })
                ->where([
                    'carts.student_id' => Auth::guard('student')->user()->id
                ])
                ->whereNull('carts.deleted_at');

            $result = DB::table('classrooms')
                ->select([
                    'classrooms.id as classroom_id',
                    'classrooms.name as classroom_name',
                    'classrooms.buy_btn_disable',
                    'classrooms.description',
                    'classrooms.image',
                    'classrooms.price',
                    'classrooms.session_total',
                    'classrooms.session_duration',
                    DB::raw("CONCAT('{$path}',classrooms.image) as image_url"),
                    DB::raw("(
                        CASE
                            WHEN carts.transaction_detail_id IS NOT NULL OR carts.classroom_id IS NOT NULL THEN
                                1
                            ELSE
                                0
                        END
                    )AS is_buy")
                ])
                ->leftJoinSub($cart, 'carts', function ($join) {
                    $join->on('classrooms.id', '=', 'carts.classroom_id');
                })
                ->where('classrooms.is_discount', false)
                ->where(function($query) use($request){
                    if($request->package_type){
                        $query->where('classrooms.package_type',$request->package_type);
                    }

                    if($request->init_class_category){
                        $query->where('classrooms.classroom_category_id',$request->init_class_category);
                    }

                    if($request->init_class_sub_category){
                        $query->where('classrooms.sub_classroom_category_id',$request->init_class_sub_category);
                    }
                })
                ->whereNull('classrooms.deleted_at')
                ->where('classrooms.hide','!=', true)
                ->distinct('classrooms.id')
                ->paginate(6);

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

    public function get_special_offer()
    {
        try {
            $path = Storage::disk('s3')->url('/');

            $result = DB::table('discounts')
                ->select([
                    'discounts.id',
                    'discounts.classroom_id',
                    'discounts.discount',
                    'classrooms.price',
                    DB::raw("CONCAT('{$path}',classrooms.image) as image_url"),
                    DB::raw("(classrooms.price::integer - (classrooms.price::integer * discounts.discount::integer)/100) AS price_discount"),
                ])
                ->leftJoin('classrooms','discounts.classroom_id','=','classrooms.id')
                ->whereRaw("discounts.date_start::timestamp <= now()::timestamp")
                ->whereRaw("discounts.date_end::timestamp >= now()::timestamp")
                ->whereNull([
                    'discounts.deleted_at',
                    'classrooms.deleted_at'
                ])
                ->get();

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

    public function special_offer_detail($id)
    {
        try {
            $path = Storage::disk('s3')->url('/');

            $transaction_detail = DB::table('transaction_details')
                ->select([
                    'transaction_details.cart_id',
                    'transaction_details.id',
                ])
                ->whereNull('transaction_details.deleted_at');

            $cart = DB::table('carts')
                ->select([
                    'carts.classroom_id',
                    'transaction_details.id as transaction_detail_id',
                ])
                ->leftJoinSub($transaction_detail, 'transaction_details', function ($join) {
                    $join->on('carts.id', '=', 'transaction_details.cart_id');
                })
                ->where([
                    'carts.student_id' => Auth::guard('student')->user()->id
                ])
                ->whereNull('carts.deleted_at');

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name as classroom_name',
                    'classrooms.buy_btn_disable',
                    'classrooms.description',
                    'classrooms.image',
                    'classrooms.price',
                    'classrooms.session_total',
                    'classrooms.session_duration',
                    DB::raw("CONCAT('{$path}',classrooms.image) as image_url"),
                    DB::raw("(
                        CASE
                            WHEN carts.transaction_detail_id IS NOT NULL OR carts.classroom_id IS NOT NULL THEN
                                1
                            ELSE
                                0
                        END
                    )AS is_buy")
                ])
                ->leftJoinSub($cart, 'carts', function ($join) {
                    $join->on('classrooms.id', '=', 'carts.classroom_id');
                })
                ->whereNull('classrooms.deleted_at')
                ->where('classrooms.hide','!=', true)
                ->distinct('classrooms.id');

            $result = DB::table('discounts')
                ->select([
                    'discounts.id',
                    'discounts.classroom_id',
                    'discounts.discount',
                    'classrooms.classroom_name',
                    'classrooms.buy_btn_disable',
                    'classrooms.description',
                    'classrooms.image',
                    'classrooms.price',
                    'classrooms.session_total',
                    'classrooms.session_duration',
                    'classrooms.image_url',
                    'classrooms.is_buy',
                    DB::raw("(classrooms.price::integer - (classrooms.price::integer * discounts.discount::integer)/100) AS price_discount"),
                ])
                ->leftJoinSub($classroom, 'classrooms', function ($join) {
                    $join->on('discounts.classroom_id', '=', 'classrooms.id');
                })
                ->where('discounts.id', $id)
                ->where('classrooms.hide','!=', true)
                ->whereNull('discounts.deleted_at')
                ->first();

            $tools = DB::table('classroom_tools')
                ->select([
                    'tools.text as tool_name'
                ])
                ->where('classroom_id',$result->classroom_id)
                ->leftJoin('tools','classroom_tools.tool_id','=','tools.id')
                ->get();

            $coach = DB::table('coach_classrooms')
                ->select([
                    'coaches.name as coach_name',
                    'coaches.id as coach_id',
                    DB::raw("CONCAT('{$path}',coaches.image) as coach_image_url"),
                ])
                ->leftJoin('coaches','coach_classrooms.coach_id','=','coaches.id')
                ->where('classroom_id',$result->classroom_id)
                ->whereNull([
                    'coach_classrooms.deleted_at',
                    'coaches.deleted_at',
                ])
                ->get();

            $result->tools = $tools;
            $result->coach = $coach;

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

    public function get_classroom_by_sub_category_id(Request $request, $sub_classroom_category_id)
    {
        try {
            $path = Storage::disk('s3')->url('/');

            $transaction_detail = DB::table('transaction_details')
                ->select([
                    'transaction_details.cart_id',
                    'transaction_details.id',
                ])
                ->whereNull('transaction_details.deleted_at');

            $cart = DB::table('carts')
                ->select([
                    'carts.classroom_id',
                    'transaction_details.id as transaction_detail_id',
                ])
                ->leftJoinSub($transaction_detail, 'transaction_details', function ($join) {
                    $join->on('carts.id', '=', 'transaction_details.cart_id');
                })
                ->where([
                    'carts.student_id' => Auth::guard('student')->user()->id
                ])
                ->whereNull('carts.deleted_at');

            $result = DB::table('classrooms')
                ->select([
                    'classrooms.id as classroom_id',
                    'classrooms.name as classroom_name',
                    'classrooms.buy_btn_disable',
                    'classrooms.description',
                    'classrooms.image',
                    'classrooms.price',
                    'classrooms.sub_classroom_category_id',
                    'classrooms.session_total',
                    'classrooms.session_duration',
                    DB::raw("CONCAT('{$path}',classrooms.image) as image_url"),
                    DB::raw("(
                        CASE
                            WHEN carts.transaction_detail_id IS NOT NULL OR carts.classroom_id IS NOT NULL THEN
                                1
                            ELSE
                                0
                        END
                    )AS is_buy")
                ])
                ->leftJoinSub($cart, 'carts', function ($join) {
                    $join->on('classrooms.id', '=', 'carts.classroom_id');
                })
                ->where('classrooms.deleted_at')
                ->where('classrooms.sub_classroom_category_id',$sub_classroom_category_id)
                ->where(function($query) use($request) {
                    if(!empty($request->type_classroom)){
                        $query->where('classrooms.package_type',$request->type_classroom);
                    }
                })
                ->distinct('classrooms.id')
                ->where('classrooms.hide','!=', true)
                ->paginate(6);

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

            if($request->sub_classroom_category_id){
                $_SESSION["sub_classroom_category_id"] = $request->sub_classroom_category_id;
            }

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
                    DB::raw("CONCAT('{$path}',session_videos.image) as image_url"),
                    'coaches.name as coach_name',
                    'expertises.name as expertise_name',
                ])
                ->joinSub($coach, 'coaches', function ($join) {
                    $join->on('session_videos.coach_id', '=', 'coaches.id');
                })
                ->joinSub($expertise, 'expertises', function ($join) {
                    $join->on('session_videos.expertise_id', '=', 'expertises.id');
                })
                ->where(function($query) use($request){
                    if(!empty($_SESSION["sub_classroom_category_id"])){
                        $query->where('session_videos.sub_classroom_category_id',$_SESSION["sub_classroom_category_id"]);
                    }
                })
                ->whereNull('session_videos.deleted_at')
                ->paginate(6);

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
                    'name',
                    'number',
                ])
                ->where(function($query) use($request){
                    if(!empty($request->classroom_category_id)){
                        $query->where('classroom_category_id',$request->classroom_category_id);
                    }
                })
                ->whereNull('deleted_at')
                ->orderBy('number')
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
            $transaction_detail = DB::table('transaction_details')
                ->select([
                    'transaction_details.cart_id',
                    'transaction_details.id',
                ])
                ->whereNull('transaction_details.deleted_at');

            $cart = DB::table('carts')
                ->select([
                    'carts.classroom_id',
                    'transaction_details.id as transaction_detail_id',
                ])
                ->leftJoinSub($transaction_detail, 'transaction_details', function ($join) {
                    $join->on('carts.id', '=', 'transaction_details.cart_id');
                })
                ->where([
                    'carts.student_id' => Auth::guard('student')->user()->id
                ])
                ->whereNull('carts.deleted_at');

            $result = DB::table('classrooms')
                ->select([
                    'classrooms.id as classroom_id',
                    'classrooms.name as classroom_name',
                    'classrooms.buy_btn_disable',
                    'classrooms.description',
                    'classrooms.classroom_category_id',
                    'classrooms.image',
                    'classrooms.price',
                    'classrooms.sub_classroom_category_id',
                    'classrooms.session_total',
                    'classrooms.session_duration',
                    DB::raw("CONCAT('{$path}',classrooms.image) as image_url"),
                    DB::raw("(
                        CASE
                            WHEN carts.transaction_detail_id IS NOT NULL OR carts.classroom_id IS NOT NULL THEN
                                1
                            ELSE
                                0
                        END
                    )AS is_buy")
                ])
                ->leftJoinSub($cart, 'carts', function ($join) {
                    $join->on('classrooms.id', '=', 'carts.classroom_id');
                })
                ->where('classrooms.deleted_at')
                ->where('classrooms.classroom_category_id',$id)
                ->where('classrooms.hide','!=', true)
                ->distinct('classrooms.id')
                ->paginate(6);

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

    public function get_master_lesson(Request $request)
    {
        try {
            $path = Storage::disk('s3')->url('/');

            $transaction = DB::table('transactions')
                ->select([
                    'transactions.id',
                    'transactions.status',
                ])
                ->where('transactions.status',2)
                ->whereNull('transactions.deleted_at');

            $transaction_detail = DB::table('transaction_details')
                ->select([
                    'transaction_details.cart_id',
                    'transaction_details.id',
                    'transactions.status',
                ])
                ->leftJoinSub($transaction, 'transactions', function ($join) {
                    $join->on('transaction_details.transaction_id', '=', 'transactions.id');
                })
                ->whereNull('transaction_details.deleted_at');

            $cart = DB::table('carts')
                ->select([
                    'carts.master_lesson_id',
                    'transaction_details.id as transaction_detail_id',
                    'transaction_details.status'
                ])
                ->joinSub($transaction_detail, 'transaction_details', function ($join) {
                    $join->on('carts.id', '=', 'transaction_details.cart_id');
                })
                // ->where([
                //     'carts.student_id' => Auth::guard('student')->user()->id
                // ])
                ->whereNull('carts.deleted_at')
                ->whereNotNull('transaction_details.id');

            $sub_master_lesson = DB::table('master_lessons')
                ->select([
                    'master_lessons.id',
                    DB::raw("COUNT(carts.master_lesson_id) as total_booking")
                ])
                ->leftJoinSub($cart, 'carts', function ($join) {
                    $join->on('master_lessons.id', '=', 'carts.master_lesson_id');
                })
                ->groupBy('master_lessons.id');

            $master_lesson = DB::table('master_lessons')
                ->select([
                    'master_lessons.id',
                    'master_lessons.name',
                    DB::raw("CONCAT('{$path}',master_lessons.poster) as poster"),
                    'master_lessons.slot',
                    'master_lessons.platform_link',
                    'master_lessons.price',
                    'master_lessons.datetime',
                    'master_lessons.description',
                    'sub_master_lesson.total_booking',
                    DB::raw("(
                        CASE
                            WHEN carts.status = 2 THEN
                                1
                            ELSE
                                0
                        END
                    )AS is_buy")
                ])
                ->leftJoinSub($sub_master_lesson, 'sub_master_lesson', function ($join) {
                    $join->on('master_lessons.id', '=', 'sub_master_lesson.id');
                })
                ->leftJoinSub($cart, 'carts', function ($join) {
                    $join->on('master_lessons.id', '=', 'carts.master_lesson_id');
                })
                ->whereRaw('master_lessons.datetime::timestamp > now()::timestamp')
                ->whereRaw('(master_lessons.slot::numeric - sub_master_lesson.total_booking::numeric) > 0')
                ->where(function($query) use($request){
                    if(!empty($request->sub_classroom_category)){
                        $query->where('master_lessons.sub_classroom_category_id',$request->sub_classroom_category);
                    }
                })
                ->whereNull('master_lessons.deleted_at')
                ->paginate(10);

            foreach ($master_lesson as $key => $value) {
                $guest_star = DB::table('guest_star_master_lessons')
                    ->select([
                        'guest_star_master_lessons.*',
                        DB::raw("CONCAT('{$path}',guest_stars.image) as image"),
                        'guest_stars.name',
                        'guest_stars.description',
                    ])
                    ->leftJoin('guest_stars','guest_star_master_lessons.guest_star_id','=','guest_stars.id')
                    ->where('master_lesson_id', $value->id)
                    ->whereNull([
                        'guest_star_master_lessons.deleted_at',
                        'guest_stars.deleted_at',
                    ])
                    ->get();

                $check_cart = DB::table('carts')
                    ->where([
                        'student_id' => Auth::guard('student')->user()->id,
                        'master_lesson_id' => $value->id
                    ])
                    ->whereNull('carts.deleted_at')
                    ->count();

                $value->guest_star = $guest_star;
                if($check_cart>0){
                    $value->is_exist_cart = true;
                }else{
                    $value->is_exist_cart = false;
                }
            }

            return response([
                "status" => 200,
                "data"      => $master_lesson,
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

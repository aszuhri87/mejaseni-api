<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Auth;
use Storage;

class VideoController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Materi'
            ],
            [
                'title' => 'Data Video Kelas'
            ],
        ];

        return view('student.video.index', [
            'title' => 'Video',
            'navigation' => $navigation,
            'list_menu' => $this->menu_student(),
        ]);
    }

    public function get_video(Request $request)
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

            $session_video = DB::table('session_videos')
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
                ->leftJoinSub($sub_classroom_category, 'sub_classroom_categories', function ($join) {
                    $join->on('session_videos.sub_classroom_category_id', '=', 'sub_classroom_categories.id');
                })
                ->leftJoinSub($coach, 'coaches', function ($join) {
                    $join->on('session_videos.coach_id', '=', 'coaches.id');
                })
                ->leftJoinSub($expertise, 'expertises', function ($join) {
                    $join->on('session_videos.expertise_id', '=', 'expertises.id');
                })
                ->whereNull('session_videos.deleted_at');

            $cart = DB::table('carts')
                ->select([
                    'carts.id',
                    'session_videos.id as session_video_id',
                    'session_videos.sub_classroom_category_id',
                    'session_videos.name',
                    'session_videos.datetime',
                    'session_videos.description',
                    'session_videos.price',
                    'session_videos.coach_id',
                    'session_videos.expertise_id',
                    'session_videos.image_url',
                    'session_videos.coach_name',
                    'session_videos.expertise_name',
                ])
                ->leftJoinSub($session_video, 'session_videos', function ($join) {
                    $join->on('carts.session_video_id', '=', 'session_videos.id');
                })
                ->whereNotNull('carts.session_video_id');

            $transaction_detail = DB::table('transaction_details')
                ->select([
                    'transaction_details.transaction_id',
                    'carts.session_video_id',
                    'carts.sub_classroom_category_id',
                    'carts.name',
                    'carts.datetime',
                    'carts.description',
                    'carts.price',
                    'carts.coach_id',
                    'carts.expertise_id',
                    'carts.image_url',
                    'carts.coach_name',
                    'carts.expertise_name',
                ])
                ->joinSub($cart, 'carts', function ($join) {
                    $join->on('transaction_details.cart_id', '=', 'carts.id');
                });

            $result = DB::table('transactions')
                ->select([
                    'transaction_details.session_video_id',
                    'transaction_details.sub_classroom_category_id',
                    'transaction_details.name',
                    'transaction_details.datetime',
                    'transaction_details.description',
                    'transaction_details.price',
                    'transaction_details.coach_id',
                    'transaction_details.expertise_id',
                    'transaction_details.image_url',
                    'transaction_details.coach_name',
                    'transaction_details.expertise_name',
                ])
                ->joinSub($transaction_detail, 'transaction_details', function ($join) {
                    $join->on('transactions.id', '=', 'transaction_details.transaction_id');
                })
                ->where(function($query) use($request){
                    if(!empty($request->sub_classroom_category_id)){
                        $query->where('transaction_details.sub_classroom_category_id',$request->sub_classroom_category_id);
                    }
                })
                ->where('transactions.student_id',Auth::guard('student')->user()->id)
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

    public function video_detail($id)
    {
        $navigation = [
            [
                'title' => 'Data Buy New Package'
            ],
            [
                'title' => 'Video Course'
            ],
        ];
        $path = Storage::disk('s3')->url('/');
        $sub_classroom_category = DB::table('sub_classroom_categories')
            ->select([
                'sub_classroom_categories.id',
                'sub_classroom_categories.name',
                'sub_classroom_categories.image',
            ])
            ->whereNull('sub_classroom_categories.deleted_at');

        $coach = DB::table('coaches')
            ->select([
                'coaches.id',
                'coaches.name',
            ])
            ->whereNull('deleted_at');

        $result = DB::table('session_videos')
            ->select([
                'session_videos.id',
                'session_videos.sub_classroom_category_id',
                'session_videos.expertise_id',
                'session_videos.coach_id',
                'session_videos.name',
                'session_videos.datetime',
                'session_videos.description',
                'session_videos.price',
                'coaches.name as coach_name',
                'sub_classroom_categories.name as sub_classroom_category_name',
                DB::raw("CONCAT('{$path}',sub_classroom_categories.image) as image_url"),
            ])
            ->joinSub($coach, 'coaches', function ($join) {
                $join->on('session_videos.coach_id', '=', 'coaches.id');
            })
            ->joinSub($sub_classroom_category, 'sub_classroom_categories', function ($join) {
                $join->on('session_videos.sub_classroom_category_id', '=', 'sub_classroom_categories.id');
            })
            ->where('session_videos.id',$id)
            ->first();

        $video = DB::table('theory_videos')
            ->select([
                'theory_videos.id',
                'theory_videos.name',
                'theory_videos.is_youtube',
                'theory_videos.youtube_url as url',
            ])
            ->where('theory_videos.session_video_id',$result->id)
            ->whereNull('theory_videos.deleted_at')
            ->get();

        $file_video = DB::table('theory_video_files')
            ->select([
                'theory_video_files.id',
                'theory_video_files.name',
                'theory_video_files.description',
                'theory_video_files.url',
                'theory_video_files.updated_at',
                DB::raw("CONCAT('{$path}',theory_video_files.url) as file_url"),
            ])
            ->where('theory_video_files.session_video_id',$result->id)
            ->whereNull('theory_video_files.deleted_at')
            ->get();

        $result->video = $video;
        $result->file_video = $file_video;

        return view('student.video-detail.index', [
            'title'         => 'Materi',
            'navigation'    => $navigation,
            'list_menu'     => $this->menu_student(),
            'data'          => $result
        ]);
    }

    public function get_sub_classroom_category()
    {
        try {
            $sub_classroom_category = DB::table('sub_classroom_categories')
                ->select([
                    'sub_classroom_categories.id',
                    'sub_classroom_categories.name',
                ])
                ->whereNull('sub_classroom_categories.deleted_at');

            $session_video = DB::table('session_videos')
                ->select([
                    'session_videos.id',
                    'session_videos.sub_classroom_category_id',
                    'sub_classroom_categories.name',
                ])
                ->leftJoinSub($sub_classroom_category, 'sub_classroom_categories', function ($join) {
                    $join->on('session_videos.sub_classroom_category_id','=','sub_classroom_categories.id');
                })
                ->whereNull('session_videos.deleted_at');

            $cart = DB::table('carts')
                ->select([
                    'carts.id',
                    'session_videos.sub_classroom_category_id',
                    'session_videos.name',
                ])
                ->leftJoinSub($session_video, 'session_videos', function ($join) {
                    $join->on('carts.session_video_id','=','session_videos.id');
                })
                ->whereNotNull('carts.session_video_id')
                ->whereNull('carts.deleted_at');

            $transaction_detail = DB::table('transaction_details')
                ->select([
                    'transaction_details.transaction_id',
                    'carts.sub_classroom_category_id',
                    'carts.name',
                ])
                ->leftJoinSub($cart, 'carts', function ($join) {
                    $join->on('transaction_details.cart_id', '=', 'carts.id');
                })
                ->whereNull('transaction_details.deleted_at');

            $result = DB::table('transactions')
                ->select([
                    'transaction_details.sub_classroom_category_id',
                    'transaction_details.name',
                ])
                ->leftJoinSub($transaction_detail, 'transaction_details', function ($join) {
                    $join->on('transactions.id', '=', 'transaction_details.transaction_id');
                })
                ->where('transactions.status',2)
                ->where('transactions.student_id',Auth::guard('student')->user()->id)
                ->whereNull('transactions.deleted_at')
                ->distinct('transaction_details.sub_classroom_category_id')
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
}

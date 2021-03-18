<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\TransactionDetail;

use Storage;

class DashboardController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Dashboard'
            ],
        ];

        return view('admin.dashboard.index', [
            'title' => 'Dashboard',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
            'classrooms' => $this->init_data_classroom(),
            'videos' => $this->init_data_video(),
            'statistic' => [
                'classroom' => $this->statistic_classroom(),
                'video' => $this->statistic_video(),
            ]
        ]);
    }

    public function init_data_classroom()
    {
        $path = Storage::disk('s3')->url('/');

        $sub_classrooms = DB::table('transaction_details')
            ->select([
                DB::raw("COUNT(carts.classroom_id) as classroom_count"),
                'carts.classroom_id as id',
            ])
            ->leftJoin('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->leftJoin('carts', 'carts.id', '=', 'transaction_details.cart_id')
            ->whereNotNull('carts.classroom_id')
            ->where('transactions.status', 2)
            ->groupBy('carts.classroom_id');

        $classrooms = DB::table('classrooms')
            ->select([
                'classrooms.name',
                'classroom_categories.name as category',
                DB::raw("CONCAT('{$path}',classrooms.image) as image_url"),
                'sub_classrooms.classroom_count',
            ])
            ->joinSub($sub_classrooms, 'sub_classrooms', function($join){
                $join->on('classrooms.id', 'sub_classrooms.id');
            })
            ->leftJoin('classroom_categories','classroom_categories.id','=','classrooms.classroom_category_id')
            ->orderBy('sub_classrooms.classroom_count', 'desc')
            ->limit(5)
            ->get();

        return $classrooms;
    }

    public function init_data_video()
    {
        $path = Storage::disk('s3')->url('/');

        $sub_session_videos = DB::table('transaction_details')
            ->select([
                DB::raw("COUNT(carts.session_video_id) as video_count"),
                'carts.session_video_id as id',
            ])
            ->leftJoin('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->leftJoin('carts', 'carts.id', '=', 'transaction_details.cart_id')
            ->whereNotNull('carts.session_video_id')
            ->where('transactions.status', 2)
            ->groupBy('carts.session_video_id');

        $session_videos = DB::table('session_videos')
            ->select([
                'session_videos.name',
                'classroom_categories.name as category',
                'sub_session_videos.video_count',
            ])
            ->joinSub($sub_session_videos, 'sub_session_videos', function($join){
                $join->on('session_videos.id', 'sub_session_videos.id');
            })
            ->leftJoin('sub_classroom_categories','sub_classroom_categories.id','=','session_videos.sub_classroom_category_id')
            ->leftJoin('classroom_categories','classroom_categories.id','=','sub_classroom_categories.classroom_category_id')
            ->orderBy('sub_session_videos.video_count', 'desc')
            ->limit(5)
            ->get();

        return $session_videos;
    }

    public function statistic_classroom()
    {
        $classroom_sold = DB::table('transaction_details')
            ->leftJoin('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->leftJoin('carts', 'carts.id', '=', 'transaction_details.cart_id')
            ->whereNotNull('carts.classroom_id')
            ->where('transactions.status', 2)
            ->count();

        $classroom_total = DB::table('classrooms')->count();

        return [
            'sold' => $classroom_sold,
            'total' => $classroom_total
        ];
    }

    public function statistic_video()
    {
        $video_sold = DB::table('transaction_details')
            ->leftJoin('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->leftJoin('carts', 'carts.id', '=', 'transaction_details.cart_id')
            ->whereNotNull('carts.session_video_id')
            ->where('transactions.status', 2)
            ->count();

        $video_total = DB::table('session_videos')->count();

        return [
            'sold' => $video_sold,
            'total' => $video_total
        ];
    }

    public function cart_data()
    {
        try {
            $transaction_classrooms = TransactionDetail::select(['transactions.datetime'])
                ->leftJoin('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
                ->leftJoin('carts', 'carts.id', '=', 'transaction_details.cart_id')
                ->whereNotNull('carts.classroom_id')
                ->where('transactions.status', 2)
                ->get()
                ->groupBy(function($transaction) {
                    return Carbon::parse($transaction->datetime)->format('m');
                });

            $classroomMonthly = [];
            $classArr = [];

            foreach ($transaction_classrooms as $key => $value) {
                $classroomMonthly[(int)$key] = count($value);
            }

            for($i = 1; $i <= 12; $i++){
                if(!empty($classroomMonthly[$i])){
                    $classArr[] = $classroomMonthly[$i];
                }else{
                    $classArr[] = 0;
                }
            }

            $transaction_videos = TransactionDetail::select(['transactions.datetime'])
                ->leftJoin('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
                ->leftJoin('carts', 'carts.id', '=', 'transaction_details.cart_id')
                ->whereNotNull('carts.session_video_id')
                ->where('transactions.status', 2)
                ->get()
                ->groupBy(function($transaction) {
                    return Carbon::parse($transaction->datetime)->format('m');
                });

            $videoMonthly = [];
            $videosArr = [];

            foreach ($transaction_videos as $key => $value) {
                $videoMonthly[(int)$key] = count($value);
            }

            for($i = 1; $i <= 12; $i++){
                if(!empty($videoMonthly[$i])){
                    $videosArr[] = $videoMonthly[$i];
                }else{
                    $videosArr[] = 0;
                }
            }

            return response([
                "data"      => [
                    'classroom' => $classArr,
                    'video' => $videosArr
                ],
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function execute_restoration()
    {
        return DB::transaction(function () {
            return \App\Models\Classroom::where('id','638f4588-8ecd-48b0-b612-d246fe0bd18d')->withTrashed()->first()->restore();
        });
    }
}

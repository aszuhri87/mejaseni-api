<?php

namespace App\Http\Controllers\Admin\Review;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\ReviewVideoExport;

use DataTables;
use Storage;
use PDF;

class VideoController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Report'
            ],
            [
                'title' => 'Review'
            ],
            [
                'title' => 'Video'
            ],
        ];

        return view('admin.review.video.index', [
            'title' => 'Review Video',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt(Request $request)
    {
        $classroom_category = DB::table('classroom_categories')
            ->select([
                'classroom_categories.id',
                'classroom_categories.name as classroom_category_name',
            ])
            ->whereNull('classroom_categories.deleted_at');

        $sub_classroom_category = DB::table('sub_classroom_categories')
            ->select([
                'sub_classroom_categories.id',
                'sub_classroom_categories.name as sub_classroom_category_name',
                'classroom_categories.classroom_category_name',
            ])
            ->leftJoinSub($classroom_category, 'classroom_categories', function ($join) {
                $join->on('sub_classroom_categories.classroom_category_id', '=', 'classroom_categories.id');
            })
            ->whereNull('sub_classroom_categories.deleted_at');

        // total video
        $theory_video = DB::table('theory_videos')
            ->select([
                'theory_videos.session_video_id',
                DB::raw('COUNT(theory_videos.session_video_id) as total_video')
            ])
            ->whereNull('theory_videos')
            ->groupBy('theory_videos.session_video_id');
        // end total video

        // total order
        $transaction_detail = DB::table('transaction_details')
            ->select([
                'transaction_details.cart_id',
            ])
            ->whereNull('transaction_details.deleted_at');

        $cart = DB::table('carts')
            ->select([
                'carts.session_video_id',
                DB::raw('COUNT(transaction_details.cart_id) as total_order')
            ])
            ->joinSub($transaction_detail, 'transaction_details', function ($join) {
                $join->on('carts.id', '=', 'transaction_details.cart_id');
            })
            ->whereNotNull('carts.session_video_id')
            ->whereNull('carts.deleted_at')
            ->groupBy('carts.session_video_id');
        // end total order

        // total rating video
        $session_video_feedback = DB::table('session_video_feedback')
            ->select([
                'session_video_feedback.session_video_id',
                DB::raw('SUM(session_video_feedback.star) as total_rating'),
                DB::raw('COUNT(session_video_feedback.session_video_id) as total_feedback'),
            ])
            ->whereNull('session_video_feedback.deleted_at')
            ->groupBy('session_video_feedback.session_video_id');

        // end total rating video

        $data = DB::table('session_videos')
            ->select([
                'session_videos.id',
                'session_videos.name',
                'sub_classroom_categories.id as sub_classroom_category_id',
                'sub_classroom_categories.sub_classroom_category_name',
                'sub_classroom_categories.classroom_category_name',
                DB::raw('(
                    CASE
                        WHEN theory_videos.total_video IS NOT NULL THEN
                            theory_videos.total_video
                        ELSE
                            0
                    END
                )as total_video'),
                DB::raw('(
                    CASE
                        WHEN carts.total_order IS NOT NULL THEN
                            carts.total_order
                        ELSE
                            0
                    END
                )as total_order'),
                DB::raw('(
                    CASE
                        WHEN
                            session_video_feedback.total_rating IS NOT NULL AND
                            session_video_feedback.total_feedback IS NOT NULL
                        THEN
                            ROUND(session_video_feedback.total_rating/session_video_feedback.total_feedback)
                        ELSE
                            0
                    END
                )as rating'),
            ])
            ->leftJoinSub($sub_classroom_category, 'sub_classroom_categories', function ($join) {
                $join->on('session_videos.sub_classroom_category_id', '=', 'sub_classroom_categories.id');
            })
            ->leftJoinSub($theory_video, 'theory_videos', function ($join) {
                $join->on('session_videos.id', '=', 'theory_videos.session_video_id');
            })
            ->leftJoinSub($cart, 'carts', function ($join) {
                $join->on('session_videos.id', '=', 'carts.session_video_id');
            })
            ->leftJoinSub($session_video_feedback, 'session_video_feedback', function ($join) {
                $join->on('session_videos.id', '=', 'session_video_feedback.session_video_id');
            })
            ->whereNull('session_videos.deleted_at')
            ->where(function($query) use($request){
                if(!empty($request->sub_category)){
                    $query->where('sub_classroom_categories.id',$request->sub_category);
                }
                if(!empty($request->rating)){
                    $query->whereRaw("
                        CASE
                            WHEN
                                session_video_feedback.total_rating IS NOT NULL AND
                                session_video_feedback.total_feedback IS NOT NULL
                            THEN
                                ROUND(session_video_feedback.total_rating/session_video_feedback.total_feedback) = $request->rating
                        END
                    ");
                }
            })
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function detail($id)
    {
        $session_video = DB::table('session_videos')
            ->where('id',$id)
            ->whereNull('deleted_at')
            ->first();

        $navigation = [
            [
                'title' => 'Report'
            ],
            [
                'title' => 'Review'
            ],
            [
                'title' => 'Video'
            ],
            [
                'title' => $session_video->name
            ],
        ];

        return view('admin.review.video-detail.index', [
            'title' => 'Review Video',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
            'session_video' => $session_video,
        ]);
    }

    public function dt_detail(Request $request, $id)
    {
        $path = Storage::disk('s3')->url('/');

        $student = DB::table('students')
            ->select([
                'students.id',
                'students.name as student_name',
                DB::raw("CONCAT('{$path}',image) as image"),
            ])
            ->whereNull('students.deleted_at');

        $data = DB::table('session_video_feedback')
            ->select([
                'session_video_feedback.*',
                'session_video_feedback.created_at as datetime',
                'students.student_name',
                'students.image',
            ])
            ->leftJoinSub($student, 'students', function ($join) {
                $join->on('session_video_feedback.student_id', '=', 'students.id');
            })
            ->where('session_video_id',$id)
            ->whereNull('session_video_feedback.deleted_at')
            ->where(function($query) use($request){
                if(!empty($request->rating)){
                    $query->where('star',$request->rating);
                }
            })
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function print_pdf(Request $request,$id)
    {
        $session_video = DB::table('session_videos')
            ->where('id',$id)
            ->whereNull('deleted_at')
            ->first();

        $path = Storage::disk('s3')->url('/');

        $student = DB::table('students')
            ->select([
                'students.id',
                'students.name as student_name',
                DB::raw("CONCAT('{$path}',image) as image"),
            ])
            ->whereNull('students.deleted_at');

        $data = DB::table('session_video_feedback')
            ->select([
                'session_video_feedback.*',
                'session_video_feedback.created_at as datetime',
                'students.student_name',
                'students.image',
            ])
            ->leftJoinSub($student, 'students', function ($join) {
                $join->on('session_video_feedback.student_id', '=', 'students.id');
            })
            ->where('session_video_feedback.session_video_id',$id)
            ->whereNull('session_video_feedback.deleted_at')
            ->where(function($query) use($request){
                if(!empty($request->select)){
                    $query->where('star',$request->select);
                }
            })
            ->get();


        $pdf = PDF::loadview('admin.print.pdf.review-video',compact('data'));
        return $pdf->download('review-video-'.$session_video->name.'-'.date('d-m-Y'));
    }

    public function print_excel(Request $request,$id)
    {
        $session_video = DB::table('session_videos')
            ->where('id',$id)
            ->whereNull('deleted_at')
            ->first();

        return Excel::download(new ReviewVideoExport($request->select,$id), 'review-video-'.$session_video->name.'-'.date('d-m-Y').'.xlsx');
    }
}

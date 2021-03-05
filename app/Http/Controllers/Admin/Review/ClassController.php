<?php

namespace App\Http\Controllers\Admin\Review;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use DataTables;
use Storage;

class ClassController extends BaseMenu
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
                'title' => 'Class'
            ],
        ];

        return view('admin.review.class.index', [
            'title' => 'Review Class',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt(Request $request)
    {
        $path = Storage::disk('s3')->url('/');

        $classroom_category = DB::table('classroom_categories')
            ->select([
                'classroom_categories.id',
                'classroom_categories.name',
            ])
            ->whereNull('classroom_categories.deleted_at');

        $sub_classroom_category = DB::table('sub_classroom_categories')
            ->select([
                'sub_classroom_categories.id',
                'sub_classroom_categories.name',
            ])
            ->whereNull('sub_classroom_categories.deleted_at');

        $transaction_detail = DB::table('transaction_details')
            ->select([
                'transaction_details.cart_id',
            ])
            ->whereNull('transaction_details.deleted_at');

        $cart = DB::table('carts')
            ->select([
                'carts.classroom_id',
                DB::raw('COUNT(carts.classroom_id) as total_order')
            ])
            ->leftJoinSub($transaction_detail, 'transaction_details', function ($join) {
                $join->on('carts.id', '=', 'transaction_details.cart_id');
            })
            ->whereNull('carts.deleted_at')
            ->whereNotNull('transaction_details.cart_id')
            ->groupBy('carts.classroom_id');

        $classroom_feedback = DB::table('classroom_feedback')
            ->select([
                'classroom_feedback.classroom_id',
                DB::raw('COUNT(classroom_feedback.classroom_id) as total_feedback'),
                DB::raw('SUM(classroom_feedback.star) as total_star'),
            ])
            ->whereNull('classroom_feedback.deleted_at')
            ->groupBy('classroom_feedback.classroom_id');

        $data = DB::table('classrooms')
            ->select([
                'classrooms.id',
                'classrooms.name',
                'classrooms.package_type',
                'classroom_categories.name as classroom_category_name',
                DB::raw("(
                    CASE
                        WHEN package_type = 1 THEN
                            'Special'
                        ELSE
                            'Regular'
                    END
                ) as package_type"),
                DB::raw("(
                    CASE
                        WHEN sub_classroom_categories.name IS NOT NULL THEN
                            sub_classroom_categories.name
                        ELSE
                            '-'
                    END
                ) as sub_classroom_category_name"),
                DB::raw("(
                    CASE
                        WHEN carts.total_order IS NOT NULL THEN
                            carts.total_order
                        ELSE
                            0
                    END
                ) as total_order"),
                DB::raw("(
                    CASE
                        WHEN
                            classroom_feedback.total_feedback IS NOT NULL AND
                            classroom_feedback.total_star IS NOT NULL
                        THEN
                            ROUND(classroom_feedback.total_star/classroom_feedback.total_feedback)
                        ELSE
                            0
                    END
                ) as rating"),
                DB::raw("CONCAT('{$path}',image) as image"),
            ])
            ->leftJoinSub($classroom_category, 'classroom_categories', function ($join) {
                $join->on('classrooms.classroom_category_id', '=', 'classroom_categories.id');
            })
            ->leftJoinSub($sub_classroom_category, 'sub_classroom_categories', function ($join) {
                $join->on('classrooms.sub_classroom_category_id', '=', 'sub_classroom_categories.id');
            })
            ->leftJoinSub($cart, 'carts', function ($join) {
                $join->on('classrooms.id', '=', 'carts.classroom_id');
            })
            ->leftJoinSub($classroom_feedback, 'classroom_feedback', function ($join) {
                $join->on('classrooms.id', '=', 'classroom_feedback.classroom_id');
            })
            ->where(function($query) use($request){
                if(!empty($request->category)){
                    $query->where('classroom_categories.id',$request->category);
                }
                if(!empty($request->sub_category)){
                    $query->where('sub_classroom_categories.id',$request->sub_category);
                }
                if(!empty($request->package)){
                    $query->where('classrooms.package_type',$request->package);
                }
                if(!empty($request->rating)){
                    $query->whereRaw("
                        CASE
                            WHEN
                                classroom_feedback.total_feedback IS NOT NULL AND
                                classroom_feedback.total_star IS NOT NULL
                            THEN
                                ROUND(classroom_feedback.total_star/classroom_feedback.total_feedback) = $request->rating
                        END
                    ");
                }
            })
            ->whereNull('classrooms.deleted_at')
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function detail($id)
    {
        $classroom = DB::table('classrooms')
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
                'title' => $classroom->name
            ],
        ];

        return view('admin.review.class-detail.index', [
            'title' => 'Review Class',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
            'classroom' => $classroom,
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

        $data = DB::table('classroom_feedback')
            ->select([
                'classroom_feedback.*',
                'classroom_feedback.created_at as datetime',
                'students.student_name',
                'students.image',
            ])
            ->leftJoinSub($student, 'students', function ($join) {
                $join->on('classroom_feedback.student_id', '=', 'students.id');
            })
            ->where('classroom_id',$id)
            ->whereNull('classroom_feedback.deleted_at')
            ->where(function($query) use($request){
                if(!empty($request->rating)){
                    $query->where('star',$request->rating);
                }
            })
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }
}

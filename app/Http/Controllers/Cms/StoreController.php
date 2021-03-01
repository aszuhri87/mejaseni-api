<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;


use DB;


class StoreController extends Controller
{
    public function index()
    {
    	$company = Company::first();
    	$branchs = Branch::all();

    	$classroom_categories = DB::table('classroom_categories')
            ->select([
                'id',
                'name',
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->get();

        $selected_category = DB::table('classroom_categories')
            ->select([
                'id',
                'name',
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->first();

        $sub_categories = DB::table('sub_classroom_categories')
            ->select([
                'id',
                'name',
            ])
            ->where('classroom_category_id',$selected_category->id)
            ->whereNull([
                'deleted_at'
            ])
            ->get();

        $selected_sub_categories = DB::table('sub_classroom_categories')
            ->select([
                'id',
                'name',
            ])
            ->where('classroom_category_id',$selected_category->id)
            ->whereNull([
                'deleted_at'
            ])
            ->first();

        $video_courses = DB::table('session_videos')
            ->select([
                'session_videos.*',
                'coaches.name as coach',
            ])
            ->leftJoin('coaches', 'coaches.id','=','session_videos.coach_id')
            ->where('session_videos.sub_classroom_category_id',$selected_sub_categories->id)
            ->whereNull([
                'session_videos.deleted_at'
            ])
            ->get();


    	return view('cms.store.index', [
            "company" => $company, 
            "branchs" => $branchs, 
            "classroom_categories" => $classroom_categories,
            "selected_category" => $selected_category,
            "sub_categories" => $sub_categories,
            "video_courses" => $video_courses
        ]);
    }
}

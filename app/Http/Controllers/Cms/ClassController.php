<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;

use DB;
use Storage;

class ClassController extends Controller
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
        $selected_sub_category = DB::table('sub_classroom_categories')
            ->select([
                'id',
                'name',
            ])
            ->where('classroom_category_id',$selected_category->id)
            ->whereNull([
                'deleted_at'
            ])
            ->first();


        $path = Storage::disk('s3')->url('/');
        
        $classrooms = DB::table('classrooms')
            ->select([
                'classrooms.*',
                DB::raw("CONCAT('{$path}',classrooms.image) as image_url"),
            ])
            ->where('classroom_category_id',$selected_category->id)
            ->where('sub_classroom_category_id', $selected_sub_category->id)
            ->whereNull([
                'classrooms.deleted_at'
            ])
            ->get();

        $regular_class = 2;
        $regular_classrooms = DB::table('classrooms')
            ->select([
                'classrooms.*',
                'classroom_categories.name as category',
                'sub_classroom_categories.name as sub_category',
                DB::raw("CONCAT('{$path}',classrooms.image) as image_url"),
            ])
            ->leftJoin('classroom_categories','classroom_categories.id','=','classrooms.classroom_category_id')
            ->leftJoin('sub_classroom_categories','sub_classroom_categories.id','=','classrooms.sub_classroom_category_id')
            ->whereNull([
                'classrooms.deleted_at'
            ])
            ->where('classrooms.package_type',$regular_class)
            ->where('classrooms.classroom_category_id',$selected_category->id)
            ->where('classrooms.sub_classroom_category_id', $selected_sub_category->id)
            ->get();

        // dd($regular_classrooms);


    	return view('cms.class.index', [
            "company" => $company, 
            "branchs" => $branchs, 
            "classroom_categories" => $classroom_categories,
            "selected_category" => $selected_category,
            "sub_categories" => $sub_categories,
            "selected_sub_category" => $selected_sub_category,
            "classrooms" => $classrooms,
            "regular_classrooms" => $regular_classrooms,
        ]);
    }
}

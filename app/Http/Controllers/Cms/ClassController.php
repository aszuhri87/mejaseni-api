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

        $path = Storage::disk('s3')->url('/');

        $regular_class = 2;

        $classrooms = DB::table('classrooms')
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
            ->take(3)
            ->get();


    	return view('cms.class.index', [
            "company" => $company, 
            "branchs" => $branchs, 
            "classroom_categories" => $classroom_categories,
            "classrooms" => $classrooms
        ]);
    }
}

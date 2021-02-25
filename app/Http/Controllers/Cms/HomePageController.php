<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Coach;
use App\Models\Company;


use DB;
use Storage;


class HomePageController extends Controller
{
    public function index()
    {
    	$company = Company::first();
    	$branchs = Branch::all();

    	$path = Storage::disk('s3')->url('/');
        $coachs = DB::table('coaches')
            ->select([
                'coaches.*',
                DB::raw("CONCAT('{$path}',coaches.image) as image_url"),
                'expertises.name as expertise_name',
            ])
            ->leftJoin('expertises','coaches.expertise_id','=','expertises.id')
            ->whereNull([
                'coaches.deleted_at'
            ])
            ->get();

        $programs = DB::table('programs')
            ->select([
                'name',
                'description',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->get();

    	return view('cms.homepage.index',[
            "company" => $company, 
            "branchs" => $branchs, 
            "coachs" => $coachs,
            "programs" => $programs
        ]);
    }
}

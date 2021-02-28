<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Galery;


use DB;
use Storage;

class AboutController extends Controller
{
    public function index()
    {
    	$company = Company::first();
    	$branchs = Branch::all();

        $galeries = Galery::all();




    	$path = Storage::disk('s3')->url('/');
        $teams = DB::table('teams')
            ->select([
                'name',
                'position',
                'description',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'deleted_at'
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

        $working_hours = DB::table('working_hours')
            ->select(['*'])
            ->whereNull([
                'deleted_at'
            ])
            ->orderBy('number','asc')
            ->get();

        $galeries = DB::table('galeries')
            ->select([
                'galeries.*',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'galeries.deleted_at'
            ])
            ->get();

    	return view('cms.about.index', [
    		"company" => $company, 
    		"branchs" => $branchs,
    		"teams" => $teams,
    		"programs" => $programs,
            "working_hours" => $working_hours,
            "galeries" => $galeries
    	]);
    }
}

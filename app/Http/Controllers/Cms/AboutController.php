<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;


use DB;
use Storage;

class AboutController extends Controller
{
    public function index()
    {
    	$company = Company::first();
    	$branchs = Branch::all();


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

    	return view('cms.about.index', [
    		"company" => $company, 
    		"branchs" => $branchs,
    		"teams" => $teams,
    		"programs" => $programs
    	]);
    }
}

<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;

use DB;
use Storage;

class TosController extends Controller
{
    public function index()
    {
    	$company = Company::first();
    	$branchs = Branch::all();

        $path = Storage::disk('s3')->url('/');
    	$social_medias = DB::table('social_media')
            ->select([
                'url',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->get();


    	return view('cms.tos.index', [
    		"company" => $company, 
    		"branchs" => $branchs,
    		"social_medias" => $social_medias
    	]);
    }
}

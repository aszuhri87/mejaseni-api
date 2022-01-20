<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Galery;


use Auth;
use DB;
use Storage;

class GaleryController extends Controller
{
    public function index($id)
    {
    	// check if id valid
		$is_valid = Uuid::isValid($id);
    	if(!$is_valid)
    		return abort(404);


    	$galery = Galery::find($id);
    	if(!$galery)
    		return abort(404);

    	$company = Company::first();
    	$branchs = Branch::all();
        $path = Storage::disk('s3')->url('/');
        $is_registered = Auth::guard('student')->check() ? 'registered':'unregistered';
        $banner = DB::table('banners')
            ->select([
                'title',
                'description',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->where('type',$is_registered)
            ->whereNull([
                'deleted_at'
            ])
            ->first();

        $galery = DB::table('galeries')
                    ->select([
                        'id',
                        'title',
                        'description',
                        'created_at as date',
                         DB::raw("CONCAT('{$path}',image) as image_url"),
                    ])
                    ->whereNull('deleted_at')
                    ->where('id',$id)
                    ->first();

        $galeries = DB::table('galeries')
                    ->select([
                        'id',
                        'title',
                        'description',
                         DB::raw("CONCAT('{$path}',image) as image_url"),
                    ])
                    ->whereNull('deleted_at')
                    ->where('id','!=',$id)
                    ->take(3)
                    ->get();

        $social_medias = DB::table('social_media')
            ->select([
                'url',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->get();

    	return view('cms.galery.index', [
    		"company" => $company,
    		"branchs" => $branchs,
            "banner" => $banner,
    		"galery" => $galery,
    		"galeries" => $galeries,
            "social_medias" => $social_medias
    	]);
    }
}

<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;


use Auth;
use DB;
use Storage;


class StoreController extends Controller
{
    public function index()
    {
    	$company = Company::first();
    	$branchs = Branch::all();
        $path = Storage::disk('s3')->url('/');

        
        $market_places = DB::table('market_places')
            ->select([
                'market_places.*',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'market_places.deleted_at'
            ])
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

        $store_banners = DB::table('store_banners')
            ->select([
                'store_banners.*',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'store_banners.deleted_at'
            ])
            ->get();




    	return view('cms.store.index', [
            "company" => $company, 
            "branchs" => $branchs,
            "banner" => $banner,
            "store_banners" => $store_banners,
            "market_places" => $market_places,
            "social_medias" => $social_medias
        ]);
    }
}

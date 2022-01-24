<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Coach;
use App\Models\Company;
use App\Models\SocialMedia;
use App\Models\Passion;


use Auth;
use DB;
use Storage;


class HomePageController extends Controller
{
    public function index()
    {
    	$company = Company::first();
    	$branchs = Branch::all();
        $passion = Passion::first();
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

        $image_galeries = DB::table('image_galeries')
            ->select([
                DB::raw("CONCAT('{$path}',image) as image_url"),
                'link'
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->orderBy('number')
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

        $sosmed = DB::table('sosmeds')
            ->select([
                'sosmeds.id',
                'sosmeds.slug'
            ])
            ->whereNull('sosmeds.deleted_at');

        $coach_sosmed = DB::table('coach_sosmeds')
            ->select([
                'coach_sosmeds.id',
                'coach_sosmeds.coach_id',
                'coach_sosmeds.url'
            ])
            ->joinSub($sosmed, 'sosmeds', function ($join) {
                $join->on('coach_sosmeds.sosmed_id', '=', 'sosmeds.id');
            })
            ->where('sosmeds.slug','instagram')
            ->whereNull('coach_sosmeds.deleted_at');

        $coachs = DB::table('coaches')
            ->select([
                'coaches.name',
                'coaches.description',
                'coach_reviews.id',
                'expertises.name as expertise_name',
                'coach_sosmeds.url',
                DB::raw("CONCAT('{$path}',coaches.image) as image_url"),
            ])
            ->rightJoin('coach_reviews','coach_reviews.coach_id','=','coaches.id')
            ->leftJoin('expertises','coaches.expertise_id','=','expertises.id')
            ->leftJoinSub($coach_sosmed, 'coach_sosmeds', function ($join) {
                $join->on('coaches.id', '=', 'coach_sosmeds.coach_id');
            })
            ->whereNull([
                'coach_reviews.deleted_at',
                'coaches.deleted_at',
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



        $classroom_categories = DB::table('classroom_categories')
            ->select([
                'id',
                'name',
                'image',
                'description',
                'profile_coach_video_id',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->orderBy('number', 'asc')
            ->get();

    	return view('cms.homepage.index',[
            "company" => $company,
            "branchs" => $branchs,
            "passion" => $passion,
            "banner" => $banner,
            "coachs" => $coachs,
            "programs" => $programs,
            "social_medias" => $social_medias,
            "classroom_categories" => $classroom_categories,
            "image_galeries" => $image_galeries
        ]);
    }
}

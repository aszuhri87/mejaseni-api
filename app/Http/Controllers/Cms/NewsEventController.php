<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;

use Auth;
use DB;
use Storage;

class NewsEventController extends Controller
{
    public function index()
    {
        $company = Company::first();
        $branchs = Branch::all();
        $path = Storage::disk('s3')->url('/');

        $events = DB::table('events')
            ->select([
                'id',
                'title',
                'description',
                'start_at as date',
                    DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull('deleted_at')
            ->orderBy('start_at','desc')
            ->take(3)
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

        $news = DB::table('news')
            ->select([
                'id',
                'title',
                'description',
                    DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull('deleted_at')
            ->orderBy('created_at','desc')
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

        return view('cms.news-event.index', [
            "company" => $company,
            "branchs" => $branchs,
            "banner" => $banner,
            "events" => $events,
            "news" => $news,
            "social_medias" => $social_medias
        ]);
    }
}

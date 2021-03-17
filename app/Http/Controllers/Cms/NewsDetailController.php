<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

use App\Models\Branch;
use App\Models\Company;
use App\Models\News;


use DB;
use Storage;


class NewsDetailController extends Controller
{
    public function index($news_id)
    {
        // check if id valid
        $is_valid = Uuid::isValid($news_id);
        if(!$is_valid)
            return abort(404);


        $news = News::find($news_id);
        if(!$news)
            return abort(404);

    	$company = Company::first();
    	$branchs = Branch::all();
        $path = Storage::disk('s3')->url('/');

        $news = DB::table('news')
                    ->select([
                        'id',
                        'title',
                        'description',
                        'created_at as date',
                        'quill_description',
                         DB::raw("CONCAT('{$path}',image) as image_url"),
                    ])
                    ->whereNull('deleted_at')
                    ->where('id',$news_id)
                    ->first();

        $list_news = DB::table('news')
                    ->select([
                        'id',
                        'title',
                        'description',
                         DB::raw("CONCAT('{$path}',image) as image_url"),
                    ])
                    ->whereNull('deleted_at')
                    ->where('id','!=',$news_id)
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

    	return view('cms.news-detail.index', [
    		"company" => $company,
    		"branchs" => $branchs,
    		"news" => $news,
    		"list_news" => $list_news,
            "social_medias" => $social_medias
    	]);
    }
}

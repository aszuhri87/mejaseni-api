<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;


use DB;
use Storage;


class NewsDetailController extends Controller
{
    public function index($news_id)
    {
    	$company = Company::first();
    	$branchs = Branch::all();
        $path = Storage::disk('s3')->url('/');

        $news = DB::table('news')
                    ->select([
                        'id',
                        'title',
                        'description',
<<<<<<< HEAD
=======
                        'created_at as date',
>>>>>>> e8df108927713e7c148bcd913f7125010fa2aa42
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

<<<<<<< HEAD
=======
        $social_medias = DB::table('social_media')
            ->select([
                'url',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->get();

>>>>>>> e8df108927713e7c148bcd913f7125010fa2aa42
    	return view('cms.news-detail.index', [
    		"company" => $company, 
    		"branchs" => $branchs,
    		"news" => $news,
<<<<<<< HEAD
    		"list_news" => $list_news
=======
    		"list_news" => $list_news,
            "social_medias" => $social_medias
>>>>>>> e8df108927713e7c148bcd913f7125010fa2aa42
    	]);
    }
}

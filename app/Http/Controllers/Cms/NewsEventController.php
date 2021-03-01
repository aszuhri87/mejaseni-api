<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;

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
    					'date',
    					 DB::raw("CONCAT('{$path}',image) as image_url"),
    				])
    				->whereNull('deleted_at')
    				->orderBy('date','desc')
                    ->take(4)
    				->get();

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

    	return view('cms.news-event.index', [
            "company" => $company, 
            "branchs" => $branchs, 
            "events" => $events,
            "news" => $news
        ]);
    }
}

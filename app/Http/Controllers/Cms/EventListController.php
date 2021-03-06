<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;

use DB;
use Storage;

class EventListController extends Controller
{
    public function index()
    {
    	$company = Company::first();
    	$branchs = Branch::all();
        $path = Storage::disk('s3')->url('/');

        $classroom_categories = DB::table('classroom_categories')->whereNull('deleted_at')->get();


    	$events = DB::table('events')
    				->select([
    					'events.id',
    					'events.title',
    					'events.description',
    					'events.start_at as date',
                        'classroom_categories.name as category',
    					 DB::raw("CONCAT('{$path}',image) as image_url"),
    				])
                    ->leftJoin('classroom_categories','classroom_categories.id','events.classroom_category_id')
    				->whereNull('events.deleted_at')
    				->orderBy('events.start_at','desc')
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

                    
    	return view('cms.event-list.index', [
    		"company" => $company, 
    		"branchs" => $branchs,
    		"events" => $events,
            "classroom_categories" => $classroom_categories,
            "social_medias" => $social_medias
    	]);
    }
}

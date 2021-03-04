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
    	return view('cms.event-list.index', [
    		"company" => $company, 
    		"branchs" => $branchs,
    		"events" => $events
    	]);
    }
}

<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;

use DB;
use Storage;

class EventDetailController extends Controller
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
                    ->take(3)
    				->get();
    	return view('cms.event-detail.index', [
    		"company" => $company, 
    		"branchs" => $branchs,
    		"events" => $events
    	]);
    }
}

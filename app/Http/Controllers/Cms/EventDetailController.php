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
    public function index($event_id)
    {
    	$company = Company::first();
    	$branchs = Branch::all();
        $path = Storage::disk('s3')->url('/');

    	$event = DB::table('events')
    				->select([
    					'id',
    					'title',
    					'description',
    					'date',
    					 DB::raw("CONCAT('{$path}',image) as image_url"),
    				])
    				->whereNull('deleted_at')
    				->where('id',$event_id)
    				->first();
    	return view('cms.event-detail.index', [
    		"company" => $company, 
    		"branchs" => $branchs,
    		"event" => $event
    	]);
    }
}

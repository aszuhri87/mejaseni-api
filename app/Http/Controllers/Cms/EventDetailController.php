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

        $cart = DB::table('carts')
                ->whereNull('deleted_at');

    	$event = DB::table('events')
    				->select([
    					'events.*',
                        'carts.id as is_registered',
    					 DB::raw("CONCAT('{$path}',image) as image_url"),
    				])
                    ->leftJoinSub($cart, 'carts', function($join){
                        $join->on('events.id','carts.event_id');
                    })
    				->whereNull('events.deleted_at')
                    ->whereNull('events.deleted_at')
    				->where('events.id',$event_id)
    				->first();

    	return view('cms.event-detail.index', [
    		"company" => $company, 
    		"branchs" => $branchs,
    		"event" => $event
    	]);
    }
}

<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Event;

use Auth;
use DB;
use Storage;

class EventDetailController extends Controller
{
    public function index($event_id)
    {
        // check if id valid
        $is_valid = Uuid::isValid($event_id);
        if(!$is_valid)
            return abort(404);


        $event = Event::find($event_id);
        if(!$event)
            return abort(404);


    	$company = Company::first();
    	$branchs = Branch::all();
        $path = Storage::disk('s3')->url('/');

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

        $is_registered = DB::table('carts')
            ->select([
                'carts.*'
            ])
            ->leftJoin('transaction_details', 'transaction_details.cart_id', '=', 'carts.id')
            ->leftJoin('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->where('carts.event_id',$event_id)
            ->where("transactions.status", 2)
            ->where(function($query){
                if(Auth::guard('student')->check()){
                    $query->where('carts.student_id', Auth::guard('student')->user()->id);
                }else{
                    $query->where('transactions.status', 99);
                }
            })
            ->whereNull('carts.deleted_at');

        $carts = DB::table('carts')
            ->select([
                'carts.event_id',
                DB::raw("COUNT('carts.event_id') as count_participants")
            ])
            ->groupBy("carts.event_id")
            ->whereNull("carts.deleted_at");

        $event = DB::table('events')
            ->select([
                'events.*',
                'is_registered.id as is_registered',
                'carts.count_participants',
                DB::raw("CONCAT('{$path}',image) as image_url"),
                DB::raw("CASE
                    WHEN carts.count_participants IS NULL THEN 0
                    WHEN carts.count_participants < events.quota THEN 0
                    ELSE 1
                END is_full")
            ])
            ->leftJoinSub($is_registered, 'is_registered', function($join){
                $join->on('events.id','is_registered.event_id');
            })
            ->leftJoinSub($carts, 'carts', function($join){
                $join->on("events.id", "carts.event_id");
            })
            ->whereNull('events.deleted_at')
            ->where('events.id',$event_id)
            ->first();

        $social_medias = DB::table('social_media')
            ->select([
                'url',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->get();

            return response([
                'status' => 200,
                'message' => 'success',
                'data' => $event,
            ]);
    }
}

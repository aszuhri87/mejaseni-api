<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

use App\Models\Branch;
use App\Models\Company;

use Auth;
use DB;
use Storage;

class EventListController extends Controller
{
    public function index(Request $request)
    {
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

        $classroom_categories = DB::table('classroom_categories')->whereNull('deleted_at')->get();

        $social_medias = DB::table('social_media')
            ->select([
                'url',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->get();

        $classroom_category = $request->query('classroom_category');
        $start_at = $request->query('start_at');
        $end_at = $request->query('end_at');
        $search = $request->query('search');

        $page = $request->query('page');

    	$events = DB::table('events')
            ->select([
                'events.id',
                'events.title',
                'events.description',
                'events.start_at as date',
                'classroom_categories.name as category',
                DB::raw("CONCAT('{$path}',events.image) as image_url"),
            ])
            ->leftJoin('classroom_categories','classroom_categories.id','events.classroom_category_id')
            ->whereNull('events.deleted_at')
            ->orderBy('events.start_at','desc')
            ->distinct();

        if($classroom_category){
            $is_valid = Uuid::isValid($classroom_category);
            if(!$is_valid)
                return abort(404);

            $events = $events->where('classroom_category_id',$classroom_category);
        }


        if($start_at && $end_at){
            $start_at = date('Y-m-d',strtotime($start_at));
            $end_at = date('Y-m-d',strtotime($end_at));

            $events = $events->whereBetween('start_at',[$start_at, $end_at]);
        }

        if($search){
            $events = $events->where('title', 'ilike', '%'.strtolower($search).'%');
        }

        // if($page){
        //     $events = $events->skip($page * 5);
        // }

        // $events = $events->take(5)->get();
        $events = $events->get();


    	return view('cms.event-list.index', [
    		"company" => $company,
    		"branchs" => $branchs,
            "banner" => $banner,
    		"events" => $events,
            "classroom_categories" => $classroom_categories,
            "social_medias" => $social_medias
    	]);
    }

    public function paging(Request $request)
    {
        $path = Storage::disk('s3')->url('/');

        $classroom_category = $request->query('classroom_category');
        $start_at = $request->query('start_at');
        $end_at = $request->query('end_at');
        $search = $request->query('search');

        $page = $request->query('page');


        $events = DB::table('events')
            ->select([
                'events.id',
                'events.title',
                'events.description',
                'events.start_at as date',
                'classroom_categories.name as category',
                    DB::raw("CONCAT('{$path}',events.image) as image_url"),
            ])
            ->leftJoin('classroom_categories','classroom_categories.id','events.classroom_category_id')
            ->whereNull('events.deleted_at')
            ->orderBy('events.start_at','desc')
            ->distinct();

        if($classroom_category){
            $events = $events->where('classroom_category_id',$classroom_category);
        }


        if($start_at && $end_at){
            $start_at = date('Y-m-d',strtotime($start_at));
            $end_at = date('Y-m-d',strtotime($end_at));

            $events = $events->whereBetween('start_at',[$start_at, $end_at]);
        }

        if($search){
            $events = $events->where('title', 'ilike', '%'.strtolower($search).'%');
        }

        if($page){
            $events = $events->skip($page * 5);
        }

        $events = $events->take(0)->get();


        return response([
            "data"      => $events,
            "message"   => 'Successfully'
        ], 200);
    }
}

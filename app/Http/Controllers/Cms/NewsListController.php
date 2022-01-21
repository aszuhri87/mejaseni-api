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

class NewsListController extends Controller
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


        $news = DB::table('news')
            ->select([
                'id',
                'title',
                'description',
                'created_at',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull('deleted_at')
            ->orderBy('created_at','desc');

        if($classroom_category){
            $is_valid = Uuid::isValid($classroom_category);
            if(!$is_valid)
                return abort(404);

            $news = $news->where('classroom_category_id',$classroom_category);
        }


        if($start_at && $end_at){
            $start_at = date('Y-m-d',strtotime($start_at));
            $end_at = date('Y-m-d',strtotime($end_at));
            $news = $news->whereBetween('created_at',[$start_at, $end_at]);
        }

        if($search){
            $news = $news->where('title', 'ilike', '%'.strtolower($search).'%');
        }

        $news = $news->get();

        return view('cms.news-list.index', [
            "company" => $company,
            "branchs" => $branchs,
            "banner" => $banner,
            "news" => $news,
            "social_medias" => $social_medias
        ]);
    }


    public function search(Request $request)
    {
        try {

            $video_courses = DB::table('news')
                ->where('title', 'ilike', '%'.strtolower($request->search).'%')
                ->whereNull('deleted_at')
                ->take(5)
                ->get();


            return response([
                "data"      => $video_courses,
                "message"   => 'Successfully saved!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> "Internal Server Error",
            ]);
        }

    }
}

<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;


use DB;
use Storage;


class StoreDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($video_course_id)
    {
        $company = Company::first();
        $branchs = Branch::all();
        $path = Storage::disk('s3')->url('/');
        $social_medias = DB::table('social_media')
            ->select([
                'url',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->get();


        $video_course_items = DB::table('theory_videos')
            ->select([
                'theory_videos.*',
                'theory_video_resolutions.url',
                'theory_video_resolutions.name'
            ])
            ->leftJoin('theory_video_resolutions','theory_video_resolutions.theory_video_id','=','theory_videos.id')
            ->where('theory_videos.session_video_id',$video_course_id)
            ->whereNull("theory_videos.deleted_at")
            ->get();

        $video_course_item_open = DB::table('theory_videos')
            ->select([
                'theory_videos.*',
            ])
            ->leftJoin('theory_video_resolutions','theory_video_resolutions.theory_video_id','=','theory_videos.id')
            ->where('theory_videos.session_video_id',$video_course_id)
            ->whereNull("theory_videos.deleted_at")
            ->whereNull("theory_video_resolutions.deleted_at")
            ->first();


        $video_course_item_open_videos = DB::table('theory_video_resolutions')
            ->select([
                'theory_video_resolutions.url',
                'theory_video_resolutions.name'
            ])
            ->where('theory_video_resolutions.theory_video_id',$video_course_item_open->id)
            ->whereNull("theory_video_resolutions.deleted_at")
            ->get();
            

        $video_course = DB::table('session_videos')
            ->select([
                'session_videos.*',
                'coaches.name as coach',
                'expertises.name as expertise',
                DB::raw("CONCAT('{$path}',coaches.image) as coach_image_url"),
            ])
            ->leftJoin('coaches', 'coaches.id','=','session_videos.coach_id')
            ->leftJoin('expertises', 'expertises.id','=','session_videos.expertise_id')
            ->where('session_videos.id',$video_course_id)
            ->whereNull([
                'session_videos.deleted_at'
            ])
            ->first();

        

        return view('cms.store-detail.index', [
            "company" => $company, 
            "branchs" => $branchs,
            "video_course_items" => $video_course_items,
            "video_course" => $video_course,
            "social_medias" => $social_medias
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

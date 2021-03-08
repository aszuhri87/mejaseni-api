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
            ])
            ->where('theory_videos.session_video_id',$video_course_id)
            ->whereNull("theory_videos.deleted_at")
            ->orderBy('theory_videos.number')
            ->get();

        $video_course_item_open = DB::table('theory_videos')
            ->select([
                'theory_videos.*',
            ])
            ->leftJoin('theory_video_resolutions','theory_video_resolutions.theory_video_id','=','theory_videos.id')
            ->where('theory_videos.session_video_id',$video_course_id)
            ->where('theory_videos.is_public',true)
            ->whereNull("theory_videos.deleted_at")
            ->whereNull("theory_video_resolutions.deleted_at")
            ->first();


        $video_course_item_open_videos = DB::table('theory_videos')
            ->select([
                'theory_video_resolutions.url',
                'theory_video_resolutions.name as resolution'
            ])
            ->leftJoin('theory_video_resolutions','theory_video_resolutions.theory_video_id','=','theory_videos.id')
            ->where('theory_video_resolutions.theory_video_id',$video_course_item_open->id)
            ->whereNull("theory_video_resolutions.deleted_at")
            ->get();

        $is_registered = DB::table('carts')
                ->where('session_video_id','!=',null)
                ->whereNull('deleted_at');

        $video_course = DB::table('session_videos')
            ->select([
                'session_videos.*',
                'coaches.name as coach',
                'expertises.name as expertise',
                'is_registered.id as is_registered',
                DB::raw("CONCAT('{$path}',coaches.image) as coach_image_url"),
            ])
            ->leftJoin('coaches', 'coaches.id','=','session_videos.coach_id')
            ->leftJoin('expertises', 'expertises.id','=','session_videos.expertise_id')
            ->leftJoinSub($is_registered, 'is_registered', function($join){
                $join->on('session_videos.id','is_registered.session_video_id');
            })
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
            "social_medias" => $social_medias,
            "video_course_item_open" => $video_course_item_open,
            "video_course_item_open_videos" => $video_course_item_open_videos
        ]);
    }

    
    public function get_videos($id)
    {
        
        try {
            

            $video_course_item_open_videos = DB::table('theory_videos')
                                                    ->select([
                                                        'theory_video_resolutions.url',
                                                        'theory_video_resolutions.name as resolution'
                                                    ])
                                                    ->leftJoin('theory_video_resolutions','theory_video_resolutions.theory_video_id','=','theory_videos.id')
                                                    ->where('theory_video_resolutions.theory_video_id',$id)
                                                    ->whereNull("theory_video_resolutions.deleted_at")
                                                    ->get();

            $video_html = $this->_get_videos_html($video_course_item_open_videos);

            $init_html = '<div class="content-embed__wrapper">
                    <div class="video-quality__wrapper">
                        <div class="video-quality-selected">
                            360
                        </div>
                        <div class="video-quality-item__wrapper">
                            '.$video_html.'
                        </div>
                    </div>
                    <video id="video-player" class="video-js w-100 h-100 vjs-big-play-centered" controls
                        preload="auto" data-setup="{}">
                        <source id="video-course" src="">
                        </source>
                        <p class="vjs-no-js">
                            To view this video please enable JavaScript, and consider upgrading to a
                            web browser that
                            <a href="http://videojs.com/html5-video-support/" target="_blank">
                                supports HTML5 video
                            </a>
                        </p>
                    </video>
                </div>';

            return response([
                "data"      => [
                    "video_course_item_open_videos" => $video_course_item_open_videos,
                    "html" => $init_html
                ],
                "message"   => 'Successfully'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> "Internal Server Error",
            ]);
        }
    }

    public function _get_videos_html($video_course_item_open_videos)
    {
        $html ='';
        foreach($video_course_item_open_videos as $video_course_item_open_video){
            $html .= '<div class="video-quality__item '.$video_course_item_open_video->resolution .'" data-url="'. $video_course_item_open_video->url .'">
                                    '. $video_course_item_open_video->resolution .'
                    </div>';
        }

        return $html;
                                
    }
}

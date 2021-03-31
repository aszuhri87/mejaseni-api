<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;

use Auth;
use DB;
use Storage;

class VideoCourseController extends Controller
{
    public function index()
    {
    	$company = Company::first();
    	$branchs = Branch::all();
        $path = Storage::disk('s3')->url('/');

    	$classroom_categories = DB::table('classroom_categories')
            ->select([
                'id',
                'name',
            ])
            ->whereNull([
                'deleted_at'
            ])
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


        $selected_category = null;
        $selected_sub_categories = null;

        $sub_categories = null;

        if($classroom_categories){
            $selected_category = DB::table('classroom_categories')
                ->select(['classroom_categories.id','classroom_categories.name'])
                ->leftJoin('sub_classroom_categories','sub_classroom_categories.classroom_category_id','=','classroom_categories.id')
                ->whereNull([
                    'classroom_categories.deleted_at'
                ])
                ->first();

            $sub_categories = DB::table('sub_classroom_categories')
                ->select([
                    'id',
                    'name',
                ])
                
                ->whereNull([
                    'deleted_at'
                ]);

            if($selected_category){
                $sub_categories = $sub_categories->where('classroom_category_id',$selected_category->id);
            }
            
            $sub_categories = $sub_categories->get();
            if($sub_categories && $selected_category){
                $selected_sub_categories = DB::table('sub_classroom_categories')
                                            ->select([
                                                'id',
                                                'name',
                                            ])
                                            ->where('classroom_category_id',$selected_category->id)
                                            ->whereNull([
                                                'deleted_at'
                                            ])
                                            ->first();
            }
            
            $is_has_video = DB::table('theory_videos')
            ->select([
                'theory_videos.session_video_id',
                DB::raw("COUNT('theory_videos.session_video_id') as count_public_video")
            ])
            ->groupBy("theory_videos.session_video_id")
            ->where('theory_videos.is_public',true)
            ->whereNull("theory_videos.deleted_at");

            $video_courses = DB::table('session_videos')
                ->select([
                    'session_videos.*',
                    'coaches.name as coach',
                     DB::raw("CONCAT('{$path}',session_videos.image) as image_url"),
                ])
                ->leftJoin('coaches', 'coaches.id','=','session_videos.coach_id')
                ->leftJoinSub($is_has_video, 'theory_videos', function($join){
                    $join->on("session_videos.id", "theory_videos.session_video_id");
                })
                ->where('theory_videos.count_public_video','!=',null)
                ->whereNull([
                    'session_videos.deleted_at',
                    'coaches.deleted_at',
                ]);
            
            if($selected_sub_categories){
                $video_courses = $video_courses->where('session_videos.sub_classroom_category_id',$selected_sub_categories->id);
            }

            $video_courses = $video_courses->get();
        }

    	return view('cms.video-course.index', [
            "company" => $company, 
            "branchs" => $branchs,
            "banner" => $banner, 
            "classroom_categories" => $classroom_categories,
            "selected_category" => $selected_category,
            "sub_categories" => $sub_categories,
            "video_courses" => $video_courses,
            "social_medias" => $social_medias
        ]);
    }

    public function search(Request $request)
    {
        try {

            $video_courses = DB::table('session_videos')
                ->where('name', 'ilike', '%'.strtolower($request->search).'%')
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


    public function get_sub_category($category_id)
    {
        try {
            $sub_categories = DB::table('sub_classroom_categories')
                    ->select([
                        'id',
                        'name',
                    ])
                    ->where('classroom_category_id',$category_id)
                    ->whereNull([
                        'deleted_at'
                    ])
                    ->get();

            $sub_category_html= "";
            $video_courses_html = "";

            $default_html = '<div class="mb-5 empty-store">
                              <div class="row my-5 py-5">
                                  <div class="col-12 pr-0 pr-lg-4 column-center">
                                      <img style="width: 200px;" src="/cms/assets/img/svg/empty-store.svg" alt="">
                                      <h4 class="mt-3 text-center">Wah, video course yang kamu cari <br />belum dibuat nih</h4>
                                  </div>
                              </div>
                          </div>';

            $index = 0;
            $selected_sub_category = null;
            foreach ($sub_categories as $key => $sub_category) {

                if($index == 0){
                    $selected_sub_category = $sub_category;
                    $sub_category_html .= '<button class="btn btn-tertiary mr-2 mb-2 active" data-id="'. $sub_category->id .'">'. $sub_category->name .'</button>';

                } else {
                    $sub_category_html .= '<button class="btn btn-tertiary mr-2 mb-2" data-id="'. $sub_category->id .'">'. $sub_category->name .'</button>';
                }

                $index++;
            }


            // get selected content
            if($selected_sub_category){
                $video_courses_html = $this->_get_video_courses($selected_sub_category->id);   
            }


            return response([
                "data"      => [
                    "sub_categories"     => $sub_categories,
                    "sub_category_html"  => $sub_category_html,
                    "video_courses_html" => $video_courses_html,
                    "default_html" => $default_html
                ],
                "message"   => 'Successfully saved!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }


    public function get_video_courses($sub_category_id)
    {
        try {

            $video_courses_html = $this->_get_video_courses($sub_category_id);


            return response([
                "data"      => [
                    "video_courses_html" => $video_courses_html
                ],
                "message"   => 'Successfully saved!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }


    /*
        Generate HTML for video courses
    */
    public function _get_video_courses($selected_sub_category_id)
    {
        $default_html = '<div class="mb-5 empty-store">
                        <div class="row my-5 py-5">
                            <div class="col-12 pr-0 pr-lg-4 column-center">
                                <img style="width: 200px;" src="/cms/assets/img/svg/empty-store.svg" alt="">
                                <h4 class="mt-3 text-center">Wah, video course yang kamu cari <br />belum dibuat nih</h4>
                            </div>
                        </div>
                    </div>';
        $video_courses_html = "";

        $path = Storage::disk('s3')->url('/');
        $is_has_video = DB::table('theory_videos')
            ->select([
                'theory_videos.session_video_id',
                DB::raw("COUNT('theory_videos.session_video_id') as count_public_video")
            ])
            ->groupBy("theory_videos.session_video_id")
            ->where('theory_videos.is_public',true)
            ->whereNull("theory_videos.deleted_at");

        $video_courses = DB::table('session_videos')
                            ->select([
                                'session_videos.*',
                                'coaches.name as coach',
                                DB::raw("CONCAT('{$path}',session_videos.image) as image_url"),
                            ])
                            ->leftJoin('coaches', 'coaches.id','=','session_videos.coach_id')
                            ->leftJoinSub($is_has_video, 'theory_videos', function($join){
                                $join->on("session_videos.id", "theory_videos.session_video_id");
                            })
                            ->where('session_videos.sub_classroom_category_id',$selected_sub_category_id)
                            ->where('theory_videos.count_public_video','!=',null)
                            ->whereNull([
                                'session_videos.deleted_at',
                                'coaches.deleted_at',
                            ])
                            ->get();

        foreach ($video_courses as $key => $video_course) {
            $video_courses_html .= '<div class="row mb-5 pb-2">
                                    <div class="col-xl-3 mb-3 mb-md-0">
                                      <a href="#">
                                        <figure><img src="'.(isset($video_course->image_url) ? $video_course->image_url:"").'" /></figure>
                                      </a>
                                    </div>
                                    <div class="col-xl-9 px-4">
                                      <div class="badge-left">
                                        <a href="/video-course/'.$video_course->id.'/detail" target="_blank">
                                          <h3 class="ml-2 mt-2 mt-md-4 mt-lg-0">'. (isset($video_course->name) ? $video_course->name:"") .'</h3>
                                        </a>
                                      </div>
                                      <p class="mt-3 ml-3 desc__store-content">'. (isset($video_course->description) ? $video_course->description:"") .'</p>
                                      <div class="detail__store-content ml-3 mt-3">
                                        <div class="coach-name__store-content row-center mr-4">
                                          <img src="/cms/assets/img/svg/User.svg" class="mr-2" alt="">'. (isset($video_course->coach) ? $video_course->coach:"") .'
                                        </div>
                                      </div>
                                    </div>
                                  </div>';
        }

        return $video_courses_html ? $video_courses_html:$default_html;
    }
}

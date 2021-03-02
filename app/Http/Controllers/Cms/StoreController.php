<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;


use DB;
use Storage;


class StoreController extends Controller
{
    public function index()
    {
    	$company = Company::first();
    	$branchs = Branch::all();

    	$classroom_categories = DB::table('classroom_categories')
            ->select([
                'id',
                'name',
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->get();

        $selected_category = DB::table('classroom_categories')
            ->select([
                'id',
                'name',
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->first();

        $sub_categories = DB::table('sub_classroom_categories')
            ->select([
                'id',
                'name',
            ])
            ->where('classroom_category_id',$selected_category->id)
            ->whereNull([
                'deleted_at'
            ])
            ->get();

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

        $video_courses = DB::table('session_videos')
            ->select([
                'session_videos.*',
                'coaches.name as coach',
            ])
            ->leftJoin('coaches', 'coaches.id','=','session_videos.coach_id')
            ->where('session_videos.sub_classroom_category_id',$selected_sub_categories->id)
            ->whereNull([
                'session_videos.deleted_at'
            ])
            ->get();

        $path = Storage::disk('s3')->url('/');
        $market_places = DB::table('market_places')
            ->select([
                'market_places.*',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'market_places.deleted_at'
            ])
            ->get();


    	return view('cms.store.index', [
            "company" => $company, 
            "branchs" => $branchs, 
            "classroom_categories" => $classroom_categories,
            "selected_category" => $selected_category,
            "sub_categories" => $sub_categories,
            "video_courses" => $video_courses,
            "market_places" => $market_places
        ]);
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
        $video_courses = DB::table('session_videos')
                            ->select([
                                'session_videos.*',
                                'coaches.name as coach',
                            ])
                            ->leftJoin('coaches', 'coaches.id','=','session_videos.coach_id')
                            ->where('session_videos.sub_classroom_category_id',$selected_sub_category_id)
                            ->whereNull([
                                'session_videos.deleted_at'
                            ])
                            ->get();

        foreach ($video_courses as $key => $video_course) {
            $video_courses_html .= '<div class="row mb-5 pb-2">
                                    <div class="col-xl-3 mb-3 mb-md-0">
                                      <a href="#">
                                        <figure><img src="/cms/assets/img/master-lesson__banner.jpg" /></figure>
                                      </a>
                                    </div>
                                    <div class="col-xl-9 px-4">
                                      <div class="badge-left">
                                        <a href="/video-course/'.$video_course->id.'/detail" target="_blank">
                                          <h3 class="ml-2 mt-2 mt-md-4 mt-lg-0">'.$video_course->name.'</h3>
                                        </a>
                                      </div>
                                      <p class="mt-3 ml-3 desc__store-content">'.$video_course->description.'</p>
                                      <div class="detail__store-content ml-3 mt-3">
                                        <div class="coach-name__store-content row-center mr-4">
                                          <img src="/cms/assets/img/svg/User.svg" class="mr-2" alt="">'.$video_course->coach.'
                                        </div>
                                      </div>
                                    </div>
                                  </div>';
        }

        return $video_courses_html ? $video_courses_html:$default_html;
    }
}

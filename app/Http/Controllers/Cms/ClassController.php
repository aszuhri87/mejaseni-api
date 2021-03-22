<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Classroom;
use App\Models\Cart;

use DB;
use Storage;
use Auth;

class ClassController extends Controller
{
    public function index($category=null)
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

    	$classroom_categories = DB::table('classroom_categories')
            ->select(['id', 'name'])
            ->whereNull([
                'deleted_at'
            ])
            ->get();

        $selected_category = DB::table('classroom_categories')
            ->select(['classroom_categories.id','classroom_categories.name'])
            ->leftJoin('sub_classroom_categories','sub_classroom_categories.classroom_category_id','=','classroom_categories.id')
            ->whereNull([
                'classroom_categories.deleted_at'
            ]);

        if($category){
            $category = str_replace("-"," ",$category);
            $selected_category = $selected_category->where('classroom_categories.name', 'ilike', '%'.strtolower($category).'%');
        }
        $selected_category = $selected_category->first();

        // jika parameter tidak valid
        if($category && $selected_category == null){
            return abort(404);
        }


        $sub_categories = null;
        $selected_sub_category = null;
        $regular_classrooms = null;

        $special_class_type = 1;
        $regular_class_type = 2;

        if($selected_category){
            $sub_categories = DB::table('sub_classroom_categories')
                    ->select(['id','name'])
                    ->where('classroom_category_id',$selected_category->id)
                    ->whereNull([
                        'deleted_at'
                    ])
                    ->get();

            $selected_sub_category = DB::table('sub_classroom_categories')
                ->select(['id','name'])
                ->where('classroom_category_id',$selected_category->id)
                ->whereNull([
                    'deleted_at'
                ])
                ->first();

            
            $is_registered = DB::table('carts')
                    ->where('classroom_id','!=',null)
                    ->whereNull('deleted_at');

            $regular_classrooms = DB::table('classrooms')
                ->select([
                    'classrooms.*',
                    'classroom_categories.name as category',
                    'sub_classroom_categories.name as sub_category',
                    'is_registered.id as is_registered',
                    DB::raw("CONCAT('{$path}',classrooms.image) as image_url"),
                ])
                ->leftJoin('classroom_categories','classroom_categories.id','=','classrooms.classroom_category_id')
                ->leftJoin('sub_classroom_categories','sub_classroom_categories.id','=','classrooms.sub_classroom_category_id')
                ->leftJoinSub($is_registered, 'is_registered', function($join){
                    $join->on('classrooms.id','is_registered.classroom_id');
                })
                ->whereNull([
                    'classrooms.deleted_at'
                ])
                ->where('classrooms.package_type',$regular_class_type)
                ->where('classrooms.classroom_category_id',$selected_category->id);

            if($selected_sub_category){
                $regular_classrooms = $regular_classrooms->where('classrooms.sub_classroom_category_id', $selected_sub_category->id);
            }

            $regular_classrooms = $regular_classrooms->get();

        }

    	return view('cms.class.index', [
            "company" => $company,
            "branchs" => $branchs,
            "banner" => $banner,
            "classroom_categories" => $classroom_categories,
            "selected_category" => $selected_category,
            "sub_categories" => $sub_categories,
            "selected_sub_category" => $selected_sub_category,
            "regular_classrooms" => $regular_classrooms,
            "social_medias" => $social_medias
        ]);
    }


    public function detail($classroom_id)
    {
        try {
            $path = Storage::disk('s3')->url('/');
            $classroom = DB::table('classrooms')
            ->select([
                'classrooms.*',
                DB::raw("CONCAT('{$path}',classrooms.image) as image_url")
            ])
            ->whereNull([
                'classrooms.deleted_at'
            ])
            ->where('id',$classroom_id)
            ->first();


            return response([
                "data"      => $classroom,
                "message"   => 'Successfully'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }


    public function get_description($classroom_id)
    {
        try {
            $classroom = DB::table('classrooms')
            ->select([
                'classrooms.*'
            ])
            ->whereNull([
                'classrooms.deleted_at'
            ])
            ->where('id',$classroom_id)
            ->first();

            $html = $this->_get_description_html($classroom);

            return response([
                "data"      => [
                    "classroom" => $classroom,
                    "html" => $html
                ],
                "message"   => 'Successfully'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_coach($classroom_id)
    {
        try {
            $path = Storage::disk('s3')->url('/');
            $coachs = DB::table('coach_classrooms')
                ->select([
                    'coaches.name',
                    'coaches.id',
                    'expertises.name as expertise',
                    DB::raw("CONCAT('{$path}',coaches.image) as image_url"),
                ])
                ->leftJoin('coaches','coach_classrooms.coach_id','=','coaches.id')
                ->leftJoin('expertises','coaches.expertise_id','=','expertises.id')
                ->where('classroom_id',$classroom_id)
                ->whereNull([
                    'coach_classrooms.deleted_at',
                    'coaches.deleted_at',
                ])
                ->get();

            $coach_html = $this->_get_coach_html($coachs);

            return response([
                "data"      => [
                    "coachs" => $coachs,
                    "html" => $coach_html
                ],
                "message"   => 'Successfully'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_tools($classroom_id)
    {
        try {
            $tools = DB::table('classroom_tools')
                    ->select([
                        'tools.text as name'
                    ])
                    ->where('classroom_id',$classroom_id)
                    ->leftJoin('tools','classroom_tools.tool_id','=','tools.id')
                    ->get();

            $tools_html = $this->_get_tools_html($tools);

            return response([
                "data"      => [
                    "tools" => $tools,
                    "html" => $tools_html
                ],
                "message"   => 'Successfully'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_guest_start($master_lesson_id)
    {
        try {
            $path = Storage::disk('s3')->url('/');
            $guest_starts = DB::table('session_videos')
                    ->select([
                        'coaches.name',
                        'coaches.id',
                        'expertises.name as expertise',
                        DB::raw("CONCAT('{$path}',coaches.image) as image_url"),
                    ])
                    ->leftJoin('coaches', 'coaches.id','=','session_videos.coach_id')
                    ->leftJoin('expertises','coaches.expertise_id','=','expertises.id')
                    ->where('session_videos.id',$master_lesson_id)
                    ->whereNull([
                        'session_videos.deleted_at'
                    ])
                    ->whereNull([
                        'coaches.deleted_at'
                    ])
                    ->get();

            $guest_starts_html = $this->_get_coach_html($guest_starts);
            return response([
                "data"      => [
                    "guest_starts" => $guest_starts,
                    "html" => $guest_starts_html
                ],
                "message"   => 'Successfully'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_master_lesson($master_lesson_id)
    {
        try {
            $path = Storage::disk('s3')->url('/');
            $master_lesson = DB::table('master_lessons')
                    ->select([
                        'master_lessons.*',
                        'platforms.name as platform'
                    ])
                    ->where('master_lessons.id',$master_lesson_id)
                    ->leftJoin('platforms','platforms.id','=','master_lessons.platform_id')
                    ->whereNull([
                        'master_lessons.deleted_at'
                    ])
                    ->first();

            $master_lesson_html = $this->_get_master_lesson_description_html($master_lesson);
            return response([
                "data"      => [
                    "master_lesson" => $master_lesson,
                    "html" => $master_lesson_html
                ],
                "message"   => 'Successfully'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }




    /**
     * Get data sub category
     *
     * @return \Illuminate\Http\Response
     */
    public function _get_sub_category_html($sub_categories, $sub_classroom_category_id)
    {
        $sub_category_html = "";
        if($sub_categories->isEmpty())
            return $sub_category_html;

        $index = 0;
        foreach ($sub_categories as $key => $sub_category) {

            if($sub_category->id == $sub_classroom_category_id){
                $sub_category_html .= '<button class="btn btn-tertiary mr-2 mb-2 active" data-id="'. $sub_category->id .'">'. $sub_category->name .'</button>';

            } else {
                $sub_category_html .= '<button class="btn btn-tertiary mr-2 mb-2" data-id="'. $sub_category->id .'">'. $sub_category->name .'</button>';
            }
            $index++;
        }

        return $sub_category_html;
    }

    public function store(Request $request)
    {
        try {
            $result = DB::transaction(function () use($request){
                $data = [];
                $student = Auth::guard('student')->user();
                if($request->type == 3){
                    $data = [
                        'student_id' => $student->id,
                        'master_lesson_id' => $request->master_lesson_id
                    ];

                }else{
                    $data = [
                        'student_id' => $student->id,
                        'classroom_id' => $request->classroom_id
                    ];
                }

                $cart = Cart::create($data);
                return $cart;
            });

            return response([
                "status"    => 200,
                "data"      => $result,
                "message"   => 'Successfully Add To Cart!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }


    /**
     * Get classroom by package
     *
     * @return \Illuminate\Http\Response
     */
    public function get_package($classroom_category_id, $sub_classroom_category_id, $package)
    {
        try {
            $special_class_type = 1;
            $regular_class_type = 2;
            $master_lesson_type = 3;
            $path = Storage::disk('s3')->url('/');
            $classroom_html = "";

            $sub_categories = DB::table('sub_classroom_categories')
                    ->select([
                        'id',
                        'name',
                    ])
                    ->where('classroom_category_id',$classroom_category_id)
                    ->whereNull([
                        'deleted_at'
                    ])
                    ->get();

            if($package == $master_lesson_type){
                $classrooms = DB::table('master_lessons')
                    ->select([
                        'master_lessons.*',
                        'classroom_categories.name as category',
                        'platforms.name as platform',
                        DB::raw("CONCAT('{$path}',poster) as image_url"),
                    ])
                    ->leftJoin('classroom_categories','classroom_categories.id','=','master_lessons.classroom_category_id')
                    ->leftJoin('platforms','platforms.id','=','master_lessons.platform_id')
                    ->where('master_lessons.classroom_category_id',$classroom_category_id)
                    ->whereNull([
                        'master_lessons.deleted_at'
                    ])
                    ->get();

                $classroom_html = $this->_get_master_lesson_html($classrooms);
            }else{
                $classrooms = DB::table('classrooms')
                    ->select([
                        'classrooms.*',
                        'classroom_categories.name as category',
                        'sub_classroom_categories.name as sub_category',
                        DB::raw("CONCAT('{$path}',classrooms.image) as image_url"),
                    ])
                    ->leftJoin('classroom_categories','classroom_categories.id','=','classrooms.classroom_category_id')
                    ->leftJoin('sub_classroom_categories','sub_classroom_categories.id','=','classrooms.sub_classroom_category_id')
                    ->whereNull([
                        'classrooms.deleted_at'
                    ])
                    ->where('classrooms.package_type',$package)
                    ->where('classroom_categories.id',$classroom_category_id);

                $is_valid = Uuid::isValid($sub_classroom_category_id);
                $classrooms = $is_valid ?
                                $classrooms->where('sub_classroom_categories.id',$sub_classroom_category_id):
                                $classrooms;

                $classrooms = $classrooms->get();
                $classroom_html = $this->_get_classroom_html($classrooms);
            }

            $sub_category_html = $this->_get_sub_category_html($sub_categories, $sub_classroom_category_id);
            $classroom_review_html = $this->_get_classroom_review_html($classrooms);


            return response([
                "data"      => [
                  "classrooms" => $classrooms,
                  "classroom_html" => $classroom_html,
                  "classroom_review_html" => $classroom_review_html,
                  "sub_category_html" => $sub_category_html
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


    public function get_classroom($sub_category_id)
    {
        try {
            $classroom_html = $this->_get_classroom($sub_category_id);

            return response([
                "data"      => [
                    "classroom_html" => $classroom_html
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



    /**
    * Utility Function
    *
    */

    public function _get_master_lesson_description_html($master_lesson)
    {
        $html = '<div class="desc__class-tab my-4">
                    <p>'. (isset($master_lesson->description) ? $master_lesson->description:"") .'</p>
                </div>
                <div class="row master-lesson__details">
                    <div class="col-6 d-flex flex-column">
                        <label>Slot</label>
                        <span>'. (isset($master_lesson->slot) ? $master_lesson->slot:"") .' Partisipan</span>
                    </div>
                    <div class="col-6 d-flex flex-column">
                        <label>Platform</label>
                        <span>'. (isset($master_lesson->platform) ? $master_lesson->platform:"") .'</span>
                    </div>
                </div>
                <div class="class-tab-summary d-flex justify-content-between flex-md-row flex-column mt-5 mb-4">
                    <div class="d-flex flex-column">
                        <span class="mt-2">Rp.'. (isset($master_lesson->price) ? number_format($master_lesson->price, 2):"0").'</span>
                    </div>
                    <div class="mt-5 mt-md-0">
                        <a  class="btn btn-primary shadow " onclick="'.$this->_is_authenticated($master_lesson->id, 3).'">Daftar Sekarang
                            <img class="ml-2" src="/cms/assets/img/svg/Sign-in.svg" alt=""></a>
                    </div>
                </div>';
        return $html;
    }

    public function _get_description_html($classroom)
    {
        $html = '<div class="content-tab-detail" style="">
                    <div class="desc__class-tab my-4">
                      <p>
                        '. (isset($classroom->description) ? $classroom->description:"") .'
                      </p>
                    </div>
                  </div>
                  <div class="class-tab-summary mb-4">
                    <div class="row">
                        <div class="col col-12 mt-4">
                            <div class="d-flex flex-column">
                              <p>'. (isset($classroom->session_total) ? $classroom->session_total:"") .' Sesi | @ '. (isset($classroom->session_duration) ? $classroom->session_duration:"") .'menit</p>
                                <span class="mt-2">Rp.'.(isset($classroom->price) ? number_format($classroom->price, 2):"").'</span>
                            </div>
                        </div>
                        <div class="col col-12 mt-4 justify-content-md-end">
                            <div class="mt-5 mt-md-0">
                                <a class="btn btn-primary shadow" onclick="'.$this->_is_authenticated($classroom->id, 1).'">Daftar
                                  Sekarang
                                  <img class="ml-2" src="cms/assets/img/svg/Sign-in.svg" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                  </div>';

        return $html;
    }

    public function _get_coach_html($coachs)
    {
        $html = "";

        foreach ($coachs as $key => $coach) {
            $html .= '<div class="coach__class-tab my-4">
                        <div class="row-center-start">
                            <div class="coach-img__class-tab mr-3">
                                <img src="'.(isset($coach->image_url) ? $coach->image_url:"").'" class="w-100 rounded-circle" alt="">
                            </div>
                            <div class="d-flex flex-column">
                                <h3>'. (isset($coach->name) ? $coach->name:"") .'</h3>
                                <span class="mt-1">'.(isset($coach->expertise) ? $coach->expertise:"").'</span>
                            </div>
                        </div>
                    </div>';
        }

        return $html;
    }

    public function _get_tools_html($tools)
    {
        $html = '<dl class="mt-4">';

        foreach ($tools as $key => $tool) {
            $html .= '<dd>- '.$tool->name.'</dd>';
        }

        $html .= '</dl>';

        return $html;
    }


    public function _getPackage($category_id)
    {
        $special_class_type = 1;
        $regular_class_type = 2;
        $special_class = DB::table('classrooms')
            ->select([
                'classrooms.classroom_category_id',
            ])
            ->whereNull("classrooms.deleted_at")
            ->where('classrooms.package_type', $special_class_type);

        $regular_class = DB::table('classrooms')
            ->select([
                'classrooms.classroom_category_id',
            ])
            ->whereNull("classrooms.deleted_at")
            ->where('classrooms.package_type', $regular_class_type);


        $master_lesson = DB::table('master_lessons')
            ->select([
                'master_lessons.classroom_category_id',
            ])
            ->whereNull("master_lessons.deleted_at");



        $data = DB::table('classroom_categories')
            ->select([
                DB::raw("
                        CASE
                            WHEN master_lessons.classroom_category_id IS NULL THEN 0
                        ELSE
                            1
                        END master_lesson "),

                DB::raw("
                        CASE
                            WHEN special_class.classroom_category_id IS NULL THEN 0
                        ELSE
                            1
                        END special_class "),

                DB::raw("
                        CASE
                            WHEN regular_class.classroom_category_id IS NULL THEN 0
                        ELSE
                            1
                        END regular_class "),

            ])
            ->leftJoinSub($special_class, 'special_class', function($join){
                $join->on("special_class.classroom_category_id", "classroom_categories.id");
            })
            ->leftJoinSub($regular_class, 'regular_class', function($join){
                $join->on("regular_class.classroom_category_id", "classroom_categories.id");
            })
            ->leftJoinSub($master_lesson, 'master_lessons', function($join){
                $join->on("master_lessons.classroom_category_id", "classroom_categories.id");
            })
            ->where('classroom_categories.id', $category_id)
            ->whereNull([
                'classroom_categories.deleted_at'
            ])
            ->first();

        return $data;
    }

    public function _get_classroom_html($classrooms)
    {

        if($classrooms->isEmpty())
            return "";

        $html ='<div class="splide pb-4" id="class-splide">
              <div class="splide__track">
                <ul class="splide__list" id="classrooms">';
        foreach ($classrooms as $key => $classroom) {
            $session_total = isset($classroom->session_total) ? $classroom->session_total:0;
            $session_duration = isset($classroom->session_duration) ? $classroom->session_duration:0;
            $html .= '<li class="splide__slide px-2 pb-5">
                        <img class="w-100 rounded" src="'. $classroom->image_url .'" alt="">
                        <div class="badge-left">
                          <h3 class="mt-4 ml-2">'. $classroom->name.'</h3>
                        </div>
                        <ul class="row-center-start class-tab mt-5 mt-md-4">
                          <li class="active tab-detail" data-id="'. $classroom->id .'" href="tab-description">Deskripsi</li>
                          <li class="tab-detail" data-id="'. $classroom->id .'" href="tab-coach">Coach</li>
                          <li class="tab-detail" data-id="'. $classroom->id .'" href="tab-tools">Tools</li>
                        </ul>
                        <div id="description">
                            <div class="content-tab-detail" style="">
                                <div class="desc__class-tab my-4">
                                  <p>
                                    '. (isset($classroom->description) ? $classroom->description:"") .'
                                  </p>
                                </div>
                              </div>
                              <div class="class-tab-summary mb-4">
                                <div class="row">
                                    <div class="col col-12 mt-4">
                                        <div class="d-flex flex-column">
                                          <p>'. (isset($classroom->session_total) ? $classroom->session_total:"") .' Sesi | @ '. (isset($classroom->session_duration) ? $classroom->session_duration:"") .'menit</p>
                                            <span class="mt-2">Rp.'. (isset($classroom->price) ? number_format($classroom->price, 2):"").'</span>
                                        </div>
                                    </div>
                                    <div class="col col-12 mt-4 justify-content-md-end">
                                        <div class="mt-5 mt-md-0">
                                            <a class="btn btn-primary shadow" onclick="'.$this->_is_authenticated($classroom->id, 1).'">Daftar
                                              Sekarang
                                              <img class="ml-2" src="cms/assets/img/svg/Sign-in.svg" alt="">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                              </div>
                        </div>
                      </li>';
        }

        $html .= '</ul>
              </div>
            </div>';

        return $html;

    }



    public function _is_authenticated($id, $type)
    {
        $master_lesson_type = 3;
        if(Auth::guard('student')->user())
            if($type == $master_lesson_type)
                return "showModalRegisterMasterLesson('".$id."')";
            else
                return "showModalRegisterClassroom('".$id."')";

        else
            return 'showModalLoginRequired()';
    }


    public function _get_master_lesson_html($master_lessons)
    {
        if($master_lessons->isEmpty()){
            return "";
        }

        $html = '<div class="splide pb-4" id="class-splide">
                  <div class="splide__track">
                    <ul class="splide__list" id="classrooms">';

        foreach ($master_lessons as $key => $master_lesson) {
            $html .= '<li class="splide__slide px-2 pb-5">
                        <img class="w-100 rounded" src="'. (isset($master_lesson->image_url) ? $master_lesson->image_url:"") .'" alt="">
                        <div class="badge-left">
                            <h4 class="mt-4 ml-2">'. (isset($master_lesson->name) ? $master_lesson->name:"") .'</h3>
                        </div>
                        <ul class="row-center-start class-tab mt-5 mt-md-4">
                            <li class="active tab-detail" data-id="'. $master_lesson->id.'" href="tab-master-lession-description">Deskripsi</li>
                            <li class="tab-detail" data-id="'. $master_lesson->id.'" href="tab-master-lession-guest-start">Guest Star</li>
                        </ul>
                        <div id="description">
                            <div class="desc__class-tab my-4">
                                <p>'.(isset($master_lesson->description) ? $master_lesson->description:"").'</p>
                            </div>
                            <div class="row master-lesson__details">
                                <div class="col-6 d-flex flex-column">
                                    <label>Slot</label>
                                    <span>'.(isset($master_lesson->slot) ? $master_lesson->slot:"").' Partisipan</span>
                                </div>
                                <div class="col-6 d-flex flex-column">
                                    <label>Platform</label>
                                    <span>'.(isset($master_lesson->platform) ? $master_lesson->platform:"").'</span>
                                </div>
                            </div>
                            <div class="class-tab-summary d-flex justify-content-between flex-md-row flex-column mt-5 mb-4">
                                <div class="d-flex flex-column">
                                    <span class="mt-2">Rp. '.(isset($master_lesson->price) ? number_format($master_lesson->price, 2):"").'</span>
                                </div>
                                <div class="mt-5 mt-md-0">
                                    <a class="btn btn-primary shadow" onclick="'.$this->_is_authenticated($master_lesson->id, 3).'">Daftar Sekarang
                                        <img class="ml-2" src="/cms/assets/img/svg/Sign-in.svg" alt=""></a>
                                </div>
                            </div>
                        </div>
                    </li>';
        }

        $html .= '</ul>
              </div>
            </div>';

        return $html;
    }


    public function _get_classroom_review_html($classrooms)
    {

        $classroom_html = "";
        if($classrooms->isEmpty())
            return $classroom_html;


        $classroom_html .= '<div class="splide mt-5" id="class-category-splide">
                                <div class="splide__track">
                                  <ul class="splide__list" id="classroom-content">';
        foreach ($classrooms as $key => $classroom) {
            $classroom_html .= '<li class="splide__slide pb-md-0 pb-3 pb-md-5">
                                  <div class="content-embed__wrapper">
                                    <img src="'.$classroom->image_url.'"
                                    data-splide-lazy="path-to-the-image" alt="">
                                    <div class="px-4 px-md-0 px-md-0 pt-4 pt-md-0">
                                      <div class="badge-left">
                                        <h3 class="mt-3 ml-2">'.$classroom->name.'</h3>
                                      </div>
                                      <p class="my-3 desc__slider-content">'.$classroom->description.'</p>
                                    </div>
                                  </div>
                                </li>';
        }

        $classroom_html .= '</ul>
                        </div>
                      </div>';

        return $classroom_html;
    }
}

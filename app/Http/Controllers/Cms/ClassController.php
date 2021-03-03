<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

use App\Models\Branch;
use App\Models\Company;

use DB;
use Storage;

class ClassController extends Controller
{
    public function index()
    {
    	$company = Company::first();
    	$branchs = Branch::all();

    	$classroom_categories = DB::table('classroom_categories')
            ->select(['id', 'name'])
            ->whereNull([
                'deleted_at'
            ])
            ->get();

        $selected_category = DB::table('classroom_categories')
            ->select(['id','name'])
            ->whereNull([
                'deleted_at'
            ])
            ->first();


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


        $path = Storage::disk('s3')->url('/');
        
        $classrooms = DB::table('classrooms')
            ->select([
                'classrooms.*',
                DB::raw("CONCAT('{$path}',classrooms.image) as image_url"),
            ])
            ->where('classroom_category_id',$selected_category->id)
            ->where('sub_classroom_category_id', $selected_sub_category->id)
            ->whereNull([
                'classrooms.deleted_at'
            ])
            ->get();

        $special_class_type = 1;
        $regular_class_type = 2;
        $regular_classrooms = DB::table('classrooms')
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
            ->where('classrooms.package_type',$regular_class_type)
            ->where('classrooms.classroom_category_id',$selected_category->id)
            ->where('classrooms.sub_classroom_category_id', $selected_sub_category->id)
            ->get();

        $packages = $this->_getPackage($selected_category->id);

    	return view('cms.class.index', [
            "company" => $company, 
            "branchs" => $branchs, 
            "classroom_categories" => $classroom_categories,
            "selected_category" => $selected_category,
            "sub_categories" => $sub_categories,
            "selected_sub_category" => $selected_sub_category,
            "classrooms" => $classrooms,
            "regular_classrooms" => $regular_classrooms,
            "packages" => $packages
        ]);
    }


    /**
     * Get data sub category
     *
     * @return \Illuminate\Http\Response
     */
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
            $classroom_html = "";

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
               $classroom_html = $this->_get_classroom($selected_sub_category->id);
            }


            return response([
                "data"      => [
                    "sub_categories"     => $sub_categories,
                    "sub_category_html"  => $sub_category_html,
                    "classroom_html" => $classroom_html,
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


            if($package == $master_lesson_type){
                $classrooms = DB::table('session_videos')
                    ->select([
                        'session_videos.*',
                        'sub_classroom_categories.classroom_category_id',
                        'coaches.name as coach',
                        'expertises.name as expertise',
                        'sub_classroom_categories.name as sub_category',
                        'classroom_categories.name as category'
                    ])
                    ->leftJoin('coaches', 'coaches.id','=','session_videos.coach_id')
                    ->leftJoin('expertises', 'expertises.id','=','session_videos.expertise_id')
                    ->leftJoin('sub_classroom_categories', 'sub_classroom_categories.id','=','session_videos.sub_classroom_category_id')
                    ->leftJoin('classroom_categories', 'classroom_categories.id','=','sub_classroom_categories.classroom_category_id')
                    ->where('classroom_categories.id',$classroom_category_id)
                    ->whereNull([
                        'session_videos.deleted_at'
                    ])
                    ->get();
            }else{
                $classrooms = DB::table('classrooms')
                    ->select([
                        'classrooms.*',
                        'classroom_categories.name as category',
                        'sub_classroom_categories.name as sub_category',
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
            }


            return response([
                "data"      => $classrooms,
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


    public function _get_classroom($sub_category_id)
    {
        
        $classroom_html = "";
        $path = Storage::disk('s3')->url('/');

        $classrooms = DB::table('classrooms')
                ->select([
                    'classrooms.*',
                    DB::raw("CONCAT('{$path}',classrooms.image) as image_url"),
                ])
                ->where('sub_classroom_category_id',$sub_category_id)
                ->whereNull([
                    'classrooms.deleted_at'
                ])
                ->get();

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

        return $classroom_html;
    }
}

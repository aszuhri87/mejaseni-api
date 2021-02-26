<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function get_classroom_category()
    {
        try {
            $result = DB::table('classroom_categories')
                ->select([
                    'id',
                    'name'
                ])
                ->whereNull('deleted_at')
                ->get();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_platform()
    {
        try {
            $result = DB::table('platforms')
                ->select([
                    'id',
                    'name'
                ])
                ->whereNull('deleted_at')
                ->get();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_guest_star()
    {
        try {
            $result = DB::table('guest_stars')
                ->select([
                    'id',
                    'name'
                ])
                ->whereNull('deleted_at')
                ->get();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_coach_by_class($id)
    {
        try {
            if($id == 'undefined'){
                return response([
                    "data"      => [],
                    "message"   => 'OK'
                ], 200);
            }

            $result = \App\Models\Coach::whereHas('classrooms', function($query) use($id){
                    $query->where('classroom_id', $id);
                })
                ->select([
                    'id',
                    'name'
                ])
                ->whereNull('deleted_at')
                ->get();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_has_classroom_category()
    {
        try {
            $result = \App\Models\ClassroomCategory::select([
                'id','name'
            ])->whereHas('sub_classroom_categories')->get();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_coach()
    {
        try {
            $result = DB::table('coaches')
                ->select([
                    'id',
                    'name'
                ])
                ->whereNull('deleted_at')
                ->get();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_expertise()
    {
        try {
            $result = DB::table('expertises')
                ->select([
                    'id',
                    'name'
                ])
                ->whereNull('deleted_at')
                ->get();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_sub_classroom_category()
    {
        try {
            $result = DB::table('sub_classroom_categories')
                ->select([
                    'id',
                    'name'
                ])
                ->whereNull('deleted_at')
                ->get();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_sub_classroom_category_by_category($id)
    {
        try {
            if($id == 'undefined'){
                return response([
                    "data"      => [],
                    "message"   => 'OK'
                ], 200);
            }

            $result = DB::table('sub_classroom_categories')
                ->select([
                    'id',
                    'name'
                ])
                ->where('classroom_category_id', $id)
                ->whereNull('deleted_at')
                ->get();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_profile_coach_videos()
    {
        try {
            $result = DB::table('profile_coach_videos')
                ->select([
                    'profile_coach_videos.id',
                    'coaches.name'
                ])
                ->leftJoin('coaches', 'coaches.id','=','profile_coach_videos.coach_id')
                ->whereNull('profile_coach_videos.deleted_at')
                ->get();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_sosmed()
    {
        try {
            $result = DB::table('sosmeds')
                ->select([
                    'sosmeds.id',
                    'sosmeds.name',
                    'sosmeds.url_icon',
                    'sosmeds.slug',
                ])
                ->whereNull('sosmeds.deleted_at')
                ->get();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_package()
    {
        try {
            $result = DB::table('packages')
                ->select([
                    'packages.id',
                    'packages.name',
                ])
                ->whereNull('packages.deleted_at')
                ->get();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_class($package_id)
    {
        try {
            $result = DB::table('classrooms')
                ->where('package_type', $package_id)
                ->whereNull('deleted_at')
                ->get();

            return response([
                "status"=>200,
                "data"  => $result,
                "message"=> 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_classroom($category_id, $sub_category_id)
    {
        try {
            $result = DB::table('classrooms')
                ->select([
                    'id',
                    'name',
                    'classroom_category_id',
                    'sub_classroom_category_id',
                ])
                ->where(function($query) use($category_id, $sub_category_id){
                    if($category_id != 'undefined'){
                        $query->where('classroom_category_id', $category_id);

                        if($sub_category_id != 'undefined'){
                            if($sub_category_id != 'null'){
                                $query->where('sub_classroom_category_id', $sub_category_id);
                            }else{
                                $query->whereNull('sub_classroom_category_id');
                            }
                        }
                    }else{
                        $query->whereNull('created_at');
                    }
                })
                ->whereNull('classrooms.deleted_at')
                ->get();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_session($classroom_id)
    {
        try {
            if($classroom_id == 'undefined'){
                return response([
                    "data"      => null,
                    "message"   => 'OK'
                ], 200);
            }

            $result = DB::table('classrooms')
                ->select([
                    'id',
                    'session_total',
                ])
                ->where('id', $classroom_id)
                ->first();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_classroom_coach()
    {
        try {
            $result = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                    'classroom_category_id',
                    'sub_classroom_category_id',
                ])
                ->join('coach_classrooms','coach_classrooms.classroom_id','classrooms.id')
                ->where('coach_classrooms.coach_id',Auth::guard('coach')->id())
                ->whereNull('classrooms.deleted_at')
                ->distinct()
                ->orderBy('classrooms.name','asc')
                ->get();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_session_coach($classroom_id)
    {
        try {
            if($classroom_id == 'undefined'){
                return response([
                    "data"      => null,
                    "message"   => 'OK'
                ], 200);
            }

            $result = DB::table('classrooms')
                ->select([
                    'id',
                    'session_total',
                ])
                ->where('id', $classroom_id)
                ->first();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }
}

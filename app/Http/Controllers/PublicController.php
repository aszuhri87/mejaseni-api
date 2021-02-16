<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
                    'sosmeds.url',
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
}

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

    public function get_profile_coach_videos()
    {
        try {
            $result = DB::table('profile_coach_videos')
                ->select([
                    'profile_coach_videos.id',
                    'coaches.name'
                ])
                ->leftJoin('coaches', 'coaches.id','=','profile_coach_videos.coach_id')
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
}

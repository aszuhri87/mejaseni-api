<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Storage;

class PackageDetailController extends BaseMenu
{
    public function index($session_video_id)
    {
        $navigation = [
            [
                'title' => 'Data Buy New Package'
            ],
            [
                'title' => 'Video Course'
            ],
        ];
        $path = Storage::disk('s3')->url('/');
        $sub_classroom_category = DB::table('sub_classroom_categories')
            ->select([
                'sub_classroom_categories.id',
                'sub_classroom_categories.name',
                'sub_classroom_categories.image',
            ])
            ->whereNull('sub_classroom_categories.deleted_at');

        $coach = DB::table('coaches')
            ->select([
                'coaches.id',
                'coaches.name',
            ])
            ->whereNull('deleted_at');

        $result = DB::table('session_videos')
            ->select([
                'session_videos.id',
                'session_videos.sub_classroom_category_id',
                'session_videos.expertise_id',
                'session_videos.coach_id',
                'session_videos.name',
                'session_videos.datetime',
                'session_videos.description',
                'session_videos.price',
                'coaches.name as coach_name',
                'sub_classroom_categories.name as sub_classroom_category_name',
                DB::raw("CONCAT('{$path}',sub_classroom_categories.image) as image_url"),
            ])
            ->joinSub($coach, 'coaches', function ($join) {
                $join->on('session_videos.coach_id', '=', 'coaches.id');
            })
            ->joinSub($sub_classroom_category, 'sub_classroom_categories', function ($join) {
                $join->on('session_videos.sub_classroom_category_id', '=', 'sub_classroom_categories.id');
            })
            ->where('session_videos.id',$session_video_id)
            ->first();

        $video = DB::table('theory_videos')
            ->select([
                'theory_videos.id',
                'theory_videos.name',
                'theory_videos.is_youtube',
                'theory_videos.url',
            ])
            ->where('theory_videos.session_video_id',$result->id)
            ->whereNull('theory_videos.deleted_at')
            ->get();

        $file_video = DB::table('theory_video_files')
            ->select([
                'theory_video_files.id',
                'theory_video_files.name',
                'theory_video_files.description',
                'theory_video_files.url',
                'theory_video_files.updated_at',
                DB::raw("CONCAT('{$path}',theory_video_files.url) as file_url"),
            ])
            ->where('theory_video_files.session_video_id',$result->id)
            ->whereNull('theory_video_files.deleted_at')
            ->get();

        $result->video = $video;
        $result->file_video = $file_video;

        return view('student.package-detail.index', [
            'title'         => 'Buy New Package',
            'navigation'    => $navigation,
            'list_menu'     => $this->menu_student(),
            'data'          => $result
        ]);
    }
}

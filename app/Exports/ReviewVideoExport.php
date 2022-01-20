<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Storage;

class ReviewVideoExport implements FromView
{
    protected $id;
    protected $star;

    public function __construct($star,$id) {
            $this->id = $id;
            $this->star = $star;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $path = Storage::disk('s3')->url('/');
        $init_star = $this->star;
        $student = DB::table('students')
            ->select([
                'students.id',
                'students.name as student_name',
                DB::raw("CONCAT('{$path}',image) as image"),
            ])
            ->whereNull('students.deleted_at');

        $data = DB::table('session_video_feedback')
            ->select([
                'session_video_feedback.*',
                'session_video_feedback.created_at as datetime',
                'students.student_name',
                'students.image',
            ])
            ->leftJoinSub($student, 'students', function ($join) {
                $join->on('session_video_feedback.student_id', '=', 'students.id');
            })
            ->where('session_video_feedback.session_video_id',$this->id)
            ->whereNull('session_video_feedback.deleted_at')
            ->where(function($query) use($init_star){
                if(!empty($init_star)){
                    $query->where('star',$init_star);
                }
            })
            ->get();

        return view('admin.print.excel.review-video', [
            'data' => $data
        ]);
    }
}

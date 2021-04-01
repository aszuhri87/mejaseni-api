<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Storage;

class ReviewClassExport implements FromView
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

        $classroom = DB::table('classrooms')
            ->where('id',$this->id)
            ->whereNull('deleted_at')
            ->first();

        $student = DB::table('students')
            ->select([
                'students.id',
                'students.name as student_name',
                DB::raw("CONCAT('{$path}',image) as image"),
            ])
            ->whereNull('students.deleted_at');

        $student_classroom = DB::table('student_classrooms')
            ->select([
                'student_classrooms.id',
                'students.student_name',
                'students.image',
            ])
            ->leftJoinSub($student, 'students', function ($join) {
                $join->on('student_classrooms.student_id', '=', 'students.id');
            })
            ->where('student_classrooms.classroom_id',$this->id)
            ->whereNull('student_classrooms.deleted_at');

        $data = DB::table('student_schedules')
            ->select([
                'session_feedback.*',
                'session_feedback.created_at as datetime',
                'student_classrooms.student_name',
                'student_classrooms.image',
            ])
            ->leftJoinSub($student_classroom, 'student_classrooms', function ($join) {
                $join->on('student_schedules.student_classroom_id', '=', 'student_classrooms.id');
            })
            ->leftJoin('session_feedback','student_schedules.id','=','session_feedback.student_schedule_id')
            ->whereNotNull([
                'session_feedback.id',
                'student_classrooms.student_name'
            ])
            ->whereNull([
                'student_schedules.deleted_at',
                'session_feedback.deleted_at'
            ])
            ->where(function($query) use($init_star){
                if(!empty($init_star)){
                    $query->where('star',$init_star);
                }
            })
            ->get();

        return view('admin.print.excel.review-class', [
            'data' => $data
        ]);
    }
}

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

        $data = DB::table('classroom_feedback')
            ->select([
                'classroom_feedback.*',
                'classroom_feedback.created_at as datetime',
                'students.student_name',
                'students.image',
            ])
            ->leftJoinSub($student, 'students', function ($join) {
                $join->on('classroom_feedback.student_id', '=', 'students.id');
            })
            ->where('classroom_feedback.classroom_id',$this->id)
            ->whereNull('classroom_feedback.deleted_at')
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

<?php

namespace App\Http\Controllers\Coach\Exercise;

use App\Http\Controllers\BaseMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReviewAssignmentController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Coach'
            ],
            [
                'title' => 'Exercise'
            ],
            [
                'title' => 'Review Assignment'
            ],
        ];

        return view('coach.exercise.review-assignment.index', [
            'title' => 'Review Assignment',
            'navigation' => $navigation,
            'list_menu' => $this->menu_coach(),
        ]);
    }

    public function dt(Request $request, $classroom_id, $session_id)
    {
        $data = DB::table('collections')
            ->select(
                'collections.id',
                'students.name',
                DB::raw("to_char(collections.upload_date, 'DD Month YYYY') as upload_date"),
                DB::raw("to_char(assignments.due_date, 'DD Month YYYY') as due_date"),
                DB::raw("COUNT(collection_feedback.id) AS status"),
            )
            ->leftjoin('collection_feedback', 'collection_feedback.collection_id', 'collections.id')
            ->leftjoin('collection_files', 'collection_files.collection_id', 'collections.id')
            ->join('assignments', 'assignments.id', 'collections.assignment_id')
            ->join('sessions', 'sessions.id', 'assignments.session_id')
            ->join('students', 'students.id', 'collections.student_id')
            ->join('student_classrooms', 'student_classrooms.student_id', 'students.id')
            ->join('classrooms', 'classrooms.id', 'student_classrooms.classroom_id')
            ->join('coach_classrooms', 'coach_classrooms.classroom_id', 'classrooms.id')
            ->join('coaches', 'coaches.id', 'coach_classrooms.coach_id')
            ->where([
                ['classrooms.id', $classroom_id],
                ['sessions.name', $session_id],
                ['coaches.id', Auth::guard('coach')->id()],
            ])
            ->orderBy('collections.created_at', 'desc')
            ->whereNull([
                'students.deleted_at'
            ])
            ->groupBy('collections.id','assignments.due_date','students.name')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

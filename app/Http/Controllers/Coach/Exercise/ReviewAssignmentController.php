<?php

namespace App\Http\Controllers\Coach\Exercise;

use App\Http\Controllers\BaseMenu;
use App\Models\CollectionFeedback;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            ->groupBy('collections.id', 'assignments.due_date', 'students.name')
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
        try {
            $path = Storage::disk('s3')->url('/');

            $result = DB::table('collections')
                ->select('collections.*')
                ->join('assignments', 'assignments.id', 'collections.assignment_id')
                ->join('sessions', 'sessions.id', 'assignments.session_id')
                ->join('students', 'students.id', 'collections.student_id')
                ->join('student_classrooms', 'student_classrooms.student_id', 'students.id')
                ->join('classrooms', 'classrooms.id', 'student_classrooms.classroom_id')
                ->join('coach_classrooms', 'coach_classrooms.classroom_id', 'classrooms.id')
                ->join('coaches', 'coaches.id', 'coach_classrooms.coach_id')
                ->where([
                    ['collections.id', $id],
                    ['coaches.id', Auth::guard('coach')->id()],
                ])
                ->whereNull([
                    'students.deleted_at'
                ])
                ->distinct()
                ->first();

            $collection_feedback = DB::table('collection_feedback')
                ->select(
                    'collection_feedback.*',
                    'coaches.name as coache_name',
                    DB::raw("CONCAT('{$path}',coaches.image) as coache_image_url"),
                )
                ->join('collections', 'collections.id', 'collection_feedback.collection_id')
                ->join('assignments', 'assignments.id', 'collections.assignment_id')
                ->join('sessions', 'sessions.id', 'assignments.session_id')
                ->join('students', 'students.id', 'collections.student_id')
                ->join('student_classrooms', 'student_classrooms.student_id', 'students.id')
                ->join('classrooms', 'classrooms.id', 'student_classrooms.classroom_id')
                ->join('coach_classrooms', 'coach_classrooms.classroom_id', 'classrooms.id')
                ->join('coaches', 'coaches.id', 'coach_classrooms.coach_id')
                ->where('collection_id', $result->id)
                ->distinct()
                ->get();

            $collection_files = DB::table('collection_files')
                ->select(
                    'collection_files.*',
                    DB::raw("CONCAT('{$path}',collection_files.url) as file_url"),
                    DB::raw("CONCAT('{$path}',students.image) as image_url"),

                )
                ->join('collections', 'collections.id', 'collection_files.collection_id')
                ->join('assignments', 'assignments.id', 'collections.assignment_id')
                ->join('sessions', 'sessions.id', 'assignments.session_id')
                ->join('students', 'students.id', 'collections.student_id')
                ->join('student_classrooms', 'student_classrooms.student_id', 'students.id')
                ->join('classrooms', 'classrooms.id', 'student_classrooms.classroom_id')
                ->join('coach_classrooms', 'coach_classrooms.classroom_id', 'classrooms.id')
                ->join('coaches', 'coaches.id', 'coach_classrooms.coach_id')
                ->where([
                    ['collections.id', $id],
                    ['coaches.id', Auth::guard('coach')->id()],
                ])
                ->where('collection_id', $result->id)
                ->distinct()
                ->get();

            $result = [
                'detail' => $result,
                'collection_feedback' => $collection_feedback,
                'collection_files' => $collection_files
            ];

            return response([
                "data"      => $result,
                "message"   => 'Successfully saved!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $path = Storage::disk('s3')->url('/');

            $result = DB::table('collections')
                ->select(
                    'collections.id',
                    'students.id as student_id',
                    'students.name',
                    'classrooms.name as classroom',
                    'collections.description',
                    DB::raw("to_char(collections.upload_date, 'DD Month YYYY') as upload_date"),
                    DB::raw("CONCAT('{$path}',students.image) as image_url"),
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
                    ['collections.id', $id],
                    ['coaches.id', Auth::guard('coach')->id()],
                ])
                ->orderBy('collections.created_at', 'desc')
                ->whereNull([
                    'students.deleted_at'
                ])
                ->groupBy(
                    'collections.id',
                    'students.id',
                    'assignments.due_date',
                    'students.name',
                    'classrooms.name',
                    'collection_files.url',
                    'students.image',
                )
                ->first();

            $collection = DB::table('collection_files')
                ->select(
                    'collection_files.*',
                    DB::raw("CONCAT('{$path}',collection_files.url) as file_url"),
                )
                ->where('collection_id', $result->id)
                ->get();

            $result = [
                'detail' => $result,
                'collection' => $collection
            ];

            return response([
                "data"      => $result,
                "message"   => 'Successfully saved!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
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
        try {

            $result = CollectionFeedback::create([
                'coach_id' => Auth::guard('coach')->id(),
                'collection_id' => $id,
                'star' => count($request->rate),
                'description' => $request->feedback,
            ]);

            return response([
                "data"      => $result,
                "message"   => 'Successfully Review!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
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

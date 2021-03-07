<?php

namespace App\Http\Controllers\Coach\Exercise;

use App\Http\Controllers\BaseMenu;
use App\Models\CollectionFeedback;
use Carbon\Carbon;
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

    public function dt_old(Request $request, $classroom_id, $session_id)
    {
        $data = DB::table('collections')
            ->select(
                'collections.id',
                'students.name',
                DB::raw("to_char(collections.upload_date, 'DD Month YYYY') as upload_date"),
                DB::raw("to_char(assignments.due_date, 'DD Month YYYY') as due_date"),
                DB::raw("COUNT(collection_feedback.id) AS status"),
            )
            ->leftJoin('collection_feedback', 'collection_feedback.collection_id', 'collections.id')
            ->leftJoin('collection_files', 'collection_files.collection_id', 'collections.id')
            ->join('assignments', 'assignments.id', 'collections.assignment_id')
            ->join('sessions', 'sessions.id', 'assignments.session_id')
            ->join('students', 'students.id', 'collections.student_id')
            ->join('student_classrooms', 'student_classrooms.student_id', 'students.id')
            ->join('classrooms', 'classrooms.id', 'student_classrooms.classroom_id')
            ->join('coach_classrooms', 'coach_classrooms.classroom_id', 'classrooms.id')
            ->join('coaches', 'coaches.id', 'coach_classrooms.coach_id');

        if (!empty($request->start_date) || !empty($request->end_date)) {
            $data->where([
                ['collections.upload_date', '>=', Carbon::parse($request->start_date)->format('Y-m-d H:i:s')],
                ['collections.upload_date', '<=', Carbon::parse($request->end_date)->format('Y-m-d H:i:s')],
            ]);
        }else{
            $data->where([
                ['collections.upload_date', '>=', Carbon::now()->format('Y-m-d H:i:s')],
                ['collections.upload_date', '<=', Carbon::now()->format('Y-m-d H:i:s')],
            ]);
        }

        $data->where([
            ['classrooms.id', $classroom_id],
            ['sessions.id', $session_id],
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
    public function dt(Request $request, $classroom_id, $session_id)
    {
        $coach_classroom = DB::table('coach_classrooms')
            ->select([
                'coach_classrooms.id',
                'coach_classrooms.coach_id',
            ])
            ->where('coach_classrooms.classroom_id',$classroom_id)
            ->whereNull('coach_classrooms.deleted_at');

        $coach_schedule = DB::table('coach_schedules')
            ->select([
                'coach_schedules.id',
                'coach_classrooms.coach_id'
            ])
            ->JoinSub($coach_classroom, 'coach_classrooms', function ($join) {
                $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
            })
            ->whereNull('coach_schedules.deleted_at');

        $student_schedule = DB::table('student_schedules')
            ->select([
                'student_schedules.session_id',
                'coach_schedules.coach_id'
            ])
            ->JoinSub($coach_schedule, 'coach_schedules', function ($join) {
                $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
            })
            ->whereNull('student_schedules.deleted_at');

        $session = DB::table('sessions')
            ->select([
                'sessions.id',
                'student_schedules.coach_id'
            ])
            ->JoinSub($student_schedule, 'student_schedules', function ($join) {
                $join->on('sessions.id', '=', 'student_schedules.session_id');
            })
            ->where('sessions.id',$session_id)
            ->whereNull('sessions.deleted_at');

        $assignment = DB::table('assignments')
            ->select([
                'assignments.id',
                'assignments.due_date',
                'assignments.upload_date',
                'sessions.coach_id'
            ])
            ->JoinSub($session, 'sessions', function ($join) {
                $join->on('assignments.session_id', '=', 'sessions.id');
            })
            ->whereNull('assignments.deleted_at');

        $student = DB::table('students')
            ->select([
                'students.id',
                'students.name',
            ])
            ->whereNull('students.deleted_at');

        $collection_feedback = DB::table('collection_feedback')
            ->select([
                'collection_feedback.collection_id',
                DB::raw("COUNT(collection_feedback.collection_id) AS status"),
            ])
            ->whereNull('collection_feedback.deleted_at')
            ->groupBy('collection_feedback.collection_id');

        $data = DB::table('collections')
            ->select([
                'collections.id',
                'assignments.coach_id',
                'students.name',
                DB::raw("to_char(collections.upload_date, 'DD Month YYYY') as upload_date"),
                DB::raw("to_char(assignments.due_date, 'DD Month YYYY') as due_date"),
                DB::raw('(
                    CASE
                        WHEN collection_feedback.status IS NOT NULL THEN
                            collection_feedback.status
                        ELSE
                            0
                    END
                ) as status')
            ])
            ->leftJoinSub($assignment, 'assignments', function ($join) {
                $join->on('collections.assignment_id', '=', 'assignments.id');
            })
            ->leftJoinSub($student, 'students', function ($join) {
                $join->on('collections.student_id', '=', 'students.id');
            })
            ->leftJoinSub($collection_feedback, 'collection_feedback', function ($join) {
                $join->on('collections.id', '=', 'collection_feedback.collection_id');
            })
            ->whereNull('collections.deleted_at')
            ->whereNotNull('assignments.coach_id')
            ->where('assignments.coach_id',Auth::guard('coach')->user()->id)
            ->where(function($query) use($request){
                if(!empty($request->start_date) && !empty($request->end_date)){
                    $query->whereDate('collections.upload_date','>=',Carbon::parse($request->start_date)->format('Y-m-d H:i:s'))
                        ->whereDate('collections.upload_date','<=',Carbon::parse($request->end_date)->format('Y-m-d H:i:s'));
                }
            })
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
                'star' => $request->rate,
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

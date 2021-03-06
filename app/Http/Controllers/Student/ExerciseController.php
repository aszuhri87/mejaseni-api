<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Auth;
use Storage;

use App\Models\TemporaryMedia;
use App\Models\Collection;
use App\Models\CollectionFile;

class ExerciseController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Exercise'
            ],
        ];

        return view('student.exercise.index', [
            'title' => 'Exercise',
            'navigation' => $navigation,
            'list_menu' => $this->menu_student(),
        ]);
    }

    public function get_class($id)
    {
        try {
            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                ])
                ->whereNull('classrooms.deleted_at');

            $result = DB::table('student_classrooms')
                ->select([
                    'classrooms.id as classroom_id',
                    'classrooms.name as classroom_name',
                ])
                ->leftJoinSub($classroom, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->where('student_id',$id)
                ->whereNull('student_classrooms.deleted_at')
                ->distinct('classrooms.id')
                ->get();

            return response([
                "status" => 200,
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

    public function get_exercise(Request $request)
    {
        try {
            $path = Storage::disk('s3')->url('/');

            $assignment = DB::table('assignments')
                ->select([
                    'assignments.session_id',
                    'assignments.id',
                    'assignments.name',
                    'assignments.description',
                    'assignments.upload_date',
                    'assignments.due_date',
                    DB::raw("CONCAT('{$path}',assignments.file_url) as file_url"),
                ])
                ->whereNull('assignments.deleted_at');

            //coach
            $coach = DB::table('coaches')
                ->select([
                    'coaches.id',
                    'coaches.name',
                ])
                ->whereNull('coaches.deleted_at');

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.id',
                    'coaches.name as coach_name',
                ])
                ->whereNull('coach_classrooms.deleted_at')
                ->joinSub($coach, 'coaches', function ($join) {
                    $join->on('coach_classrooms.coach_id', '=', 'coaches.id');
                });

            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_classrooms.coach_name',
                ])
                ->whereNull('coach_schedules.deleted_at')
                ->joinSub($coach_classroom, 'coach_classrooms', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                });

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.id',
                    'student_schedules.session_id',
                    'student_schedules.student_classroom_id',
                    'student_schedules.coach_schedule_id',
                    'assignments.id as assignment_id',
                    'assignments.name',
                    'assignments.description',
                    'assignments.upload_date',
                    'assignments.due_date',
                    'assignments.file_url',
                    'coach_schedules.coach_name',
                ])
                ->joinSub($assignment, 'assignments', function ($join) {
                    $join->on('student_schedules.session_id', '=', 'assignments.session_id');
                })
                ->joinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->whereNull('student_schedules.deleted_at');

            $collection_feedback = DB::table('collection_feedback')
                ->select([
                    'collection_feedback.collection_id',
                    'collection_feedback.star',
                ])
                ->whereNull('collection_feedback.deleted_at');

            $collection = DB::table('collections')
                ->select([
                    'collections.id',
                    'collections.assignment_id',
                    'collection_feedback.star'
                ])
                ->leftJoinSub($collection_feedback, 'collection_feedback', function ($join) {
                    $join->on('collections.id', '=', 'collection_feedback.collection_id');
                })
                ->whereNull('collections.deleted_at');

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                ]);

            $result = DB::table('student_classrooms')
                ->select([
                    'student_classrooms.id as student_classroom_id',
                    'student_classrooms.student_id',
                    'student_classrooms.classroom_id',
                    'student_schedules.session_id',
                    'student_schedules.assignment_id',
                    'student_schedules.name',
                    'student_schedules.description',
                    'student_schedules.upload_date',
                    'student_schedules.due_date',
                    'student_schedules.file_url',
                    'classrooms.name as classroom_name',
                    'student_schedules.coach_name',
                    'collections.id as collection_id',
                    DB::raw("
                        CASE
                            WHEN collections.assignment_id IS NOT NULL THEN
                                1
                            ELSE
                                0
                        END AS status_collection
                    "),
                    DB::raw("
                        CASE
                            WHEN collections.star IS NOT NULL THEN
                                1
                            ELSE
                                0
                        END AS status_collection_feedback
                    "),
                ])
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->leftJoinSub($classroom, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->leftJoinSub($collection, 'collections', function ($join) {
                    $join->on('student_schedules.assignment_id', '=', 'collections.assignment_id');
                })
                ->where('student_id',Auth::guard('student')->user()->id)
                ->where(function($query) use($request){
                    if(!empty($request->classroom_id)){
                        $query->where('student_classrooms.classroom_id',$request->classroom_id);
                    }

                    if(!empty($request->date_start) && !empty($request->date_end)){
                        $date_start = date('Y-m-d', strtotime($request->date_start));
                        $date_end = date('Y-m-d', strtotime($request->date_end));
                        $query->whereBetween('student_schedules.upload_date',[$date_start,$date_end]);
                    }
                })
                ->whereNull('student_classrooms.deleted_at')
                ->whereNotNull('student_schedules.assignment_id')
                ->get();

            return response([
                "status" => 200,
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

    public function exercise_file(Request $request)
    {
        try {
            foreach ($request->file as $key => $value) {
                $path = Storage::disk('s3')->put('media', $value);

                $temp = TemporaryMedia::create([
                    'path' => $path
                ]);
            }

            return response([
                "status" => 200,
                "data" => $temp,
                "message"   => 'Successfully saved!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function exercise_file_delete($id)
    {
        try {
            $result = TemporaryMedia::find($id);

            DB::transaction(function () use ($result) {
                $result->delete();
            });

            return response([
                "message"   => 'Successfully deleted!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function file_delete($id)
    {
        try {
            $result = CollectionFile::find($id);

            DB::transaction(function () use ($result) {
                $result->delete();
            });

            return response([
                "message"   => 'Successfully deleted!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function get_collection($id)
    {
        try {
            $result = DB::transaction(function () use($id) {
                $path = Storage::disk('s3')->url('/');

                $collection = DB::table('collections')
                    ->select([
                        'collections.id as collection_id',
                        'collections.name',
                        'collections.upload_date',
                        'collections.description',
                    ])
                    ->where('collections.id',$id)
                    ->WhereNull('collections.deleted_at')
                    ->first();

                $collection_file = DB::table('collection_files')
                    ->select([
                        'collection_files.id',
                        'collection_files.collection_id',
                        'collection_files.name',
                        DB::raw("CONCAT('{$path}',collection_files.url) as file_url"),
                    ])
                    ->whereNull('collection_files.deleted_at')
                    ->where('collection_files.collection_id',$collection->collection_id)
                    ->get();

                $collection->collection_files = $collection_file;

                return $collection;
            });

            return response([
                "status" => 200,
                "data" => $result,
                "message"   => 'OK!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $result = DB::transaction(function () use($request,$id) {
                $collection = Collection::find($id);
                $collection->update([
                    'description' => $request->description,
                    'update_date' => date('Y-m-d')
                ]);

                if(!empty($request->file)){
                    foreach ($request->file as $key => $value) {
                        CollectionFile::create([
                            'collection_id' => $collection->id,
                            'name' => $collection->name,
                            'url' => $value,
                        ]);
                    }
                }

                return $collection;
            });

            return response([
                "status" => 200,
                "data" => $result,
                "message"   => 'Successfully Update!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $result = DB::transaction(function () use($request) {
                $collection = Collection::create([
                    'assignment_id' => $request->assignment_id,
                    'name' => $request->name,
                    'student_id' => Auth::guard('student')->user()->id,
                    'description' => $request->description,
                    'upload_date' => date('Y-m-d H:i:s')
                ]);

                if(!empty($request->file)){
                    foreach ($request->file as $key => $value) {
                        CollectionFile::create([
                            'collection_id' => $collection->id,
                            'name' => $collection->name,
                            'url' => $value,
                        ]);
                    }
                }

                return $collection;
            });

            return response([
                "status" => 200,
                "data" => $result,
                "message"   => 'Successfully Submit!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function get_result($id)
    {
        try {
            $result = DB::transaction(function () use($id) {
                $path = Storage::disk('s3')->url('/');
                $coach = DB::table('coaches')
                    ->select([
                        'coaches.id',
                        'coaches.name',
                        DB::raw("CONCAT('{$path}',coaches.image) as image"),
                    ])
                    ->whereNull('coaches.deleted_at');

                $collection_feedback = DB::table('collection_feedback')
                    ->select([
                        'collection_feedback.collection_id',
                        'collection_feedback.star',
                        'collection_feedback.description',
                        'collection_feedback.coach_id',
                        'coaches.name',
                        'coaches.image',
                    ])
                    ->joinSub($coach, 'coaches', function ($join) {
                        $join->on('collection_feedback.coach_id', '=', 'coaches.id');
                    });

                $collection = DB::table('collections')
                    ->select([
                        'collections.id',
                        'collections.description',
                        'collections.name',
                        'collection_feedback.star',
                        'collection_feedback.description as feedback_description',
                        'collection_feedback.coach_id',
                        'collection_feedback.name as coach_name',
                        'collection_feedback.image',
                    ])
                    ->joinSub($collection_feedback, 'collection_feedback', function ($join) {
                        $join->on('collections.id', '=', 'collection_feedback.collection_id');
                    })
                    ->where('collections.id',$id)
                    ->first();

                $collection_file = DB::table('collection_files')
                    ->select([
                        'collection_files.name',
                        DB::raw("CONCAT('{$path}',collection_files.url) as file_url"),
                    ])
                    ->where('collection_files.collection_id',$collection->id)
                    ->whereNull('collection_files.deleted_at')
                    ->get();

                $collection->file=$collection_file;

                return $collection;
            });

            return response([
                "status" => 200,
                "data" => $result,
                "message"   => 'OK!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }
}

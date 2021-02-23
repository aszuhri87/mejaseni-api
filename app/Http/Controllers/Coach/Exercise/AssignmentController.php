<?php

namespace App\Http\Controllers\Coach\Exercise;

use App\Http\Controllers\BaseMenu;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Session;
use App\Models\TemporaryMedia;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends BaseMenu
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
                'title' => 'Assignment'
            ],
        ];

        return view('coach.exercise.assignment.index', [
            'title' => 'Assignment',
            'navigation' => $navigation,
            'list_menu' => $this->menu_coach(),
        ]);
    }

   public function store(Request $request)
   {
       try {
           if (!isset($request->file)) {
               return response([
                   "message"   => 'File harus diisi!'
               ], 400);
           }

           $result = DB::transaction(function () use ($request) {
               $session = Session::firstOrCreate([
                   'name' => $request->session_id,
                   'classroom_id' => $request->classroom_id,
               ]);

               $result = Assignment::create([
                   'session_id' => $session->id,
                   'name' => $request->name,
                   'file_url' => $request->file,
                   'description' => $request->description,
                   'upload_date' => Carbon::now()->format('Y-m-d'),
                   'due_date' => Carbon::parse($request->due_date)->format('Y-m-d H:i:s'),
               ]);

               return $result;
           });

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

   public function update(Request $request, $id)
   {
       
   }

   public function destroy($id)
   {
       try {
           $result = Assignment::find($id);

           DB::transaction(function () use ($result) {
               Storage::disk('s3')->delete($result->path);
               $result->delete();
           });

           if ($result->trashed()) {
               return response([
                   "message"   => 'Successfully deleted!'
               ], 200);
           }
       } catch (Exception $e) {
           throw new Exception($e);
           return response([
               "message" => $e->getMessage(),
           ]);
       }
   }

   public function assignment_file(Request $request)
   {
       try {
           $path = Storage::disk('s3')->put('media', $request->file);

           $temp = TemporaryMedia::create([
               'path' => $path
           ]);

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

   public function assignment_file_delete($id)
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

   public function assignment_list($classroom_id, $session_id)
   {
       try {
           date_default_timezone_set("Asia/Jakarta");

           $path = Storage::disk('s3')->url('/');

           $assignments = DB::table('assignments')
               ->select(
                   'assignments.*',
                   'classrooms.name as classrooms_name',
                   'coaches.name as coaches_name',
                   DB::raw("CONCAT('{$path}',assignments.file_url) as file_url"),
                   DB::raw("to_char(assignments.upload_date, 'DD Month YYYY') as upload_at"),
                   DB::raw("to_char(assignments.due_date, 'DD Month YYYY') as due_date")

               )
               ->join('sessions', 'sessions.id', 'assignments.session_id')
               ->join('classrooms', 'classrooms.id', 'sessions.classroom_id')
               ->join('coach_classrooms', 'coach_classrooms.classroom_id', 'classrooms.id')
               ->join('coaches', 'coaches.id', 'coach_classrooms.coach_id')
               ->where([
                   ['classrooms.id', $classroom_id],
                   ['sessions.name', $session_id],
                   ['coaches.id', Auth::guard('coach')->id()],
               ])
               ->whereNull([
                   'assignments.deleted_at'
               ])
               ->distinct()
               ->get();
               
           $classroom = Classroom::find($classroom_id);

           $result = [
               'assignment' => $assignments,
               'classroom' => $classroom->name,
               'session' => $session_id,
           ];

           return response([
               "data"      => $result,
               "message"   => 'OK',
               "status"   => 200
           ], 200);
       } catch (Exception $e) {
           throw new Exception($e);
           return response([
               "message" => $e->getMessage(),
           ]);
       }
   }

   public function assignment_download($id)
   {
       try {
           $theory = Theory::find($id);

           return $result = Storage::disk('s3')->download('/' . $theory->url);

           return response([
               "data"      => $result,
               "message"   => 'Successfully download!',
               "status"   => 200
           ], 200);
       } catch (Exception $e) {
           throw new Exception($e);
           return response([
               "message" => $e->getMessage(),
           ]);
       }
   }
}

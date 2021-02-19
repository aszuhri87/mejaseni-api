<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\BaseMenu;
use App\Models\Classroom;
use App\Models\CoachClassroom;
use App\Models\Session;
use App\Models\TemporaryMedia;
use App\Models\Theory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TheoryController extends BaseMenu
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $navigation = [
            [
                'title' => 'Coach'
            ],
            [
                'title' => 'Materi'
            ],
        ];

        return view('coach.theory.index', [
            'title' => 'Materi',
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

                $result = Theory::create([
                    'session_id' => $session->id,
                    'name' => $request->name,
                    'is_premium' => isset($request->is_premium) ? true : false,
                    'is_video' => isset($request->is_video) ? true : false,
                    'url' => $request->file,
                    'description' => $request->description,
                    'price' => isset($request->is_premium) ? $request->price : 0,
                    'upload_date' => date('Y-m-d'),
                    'confirmed' => true
                ]);

                $result = CoachClassroom::create([
                    'classroom_id' => $request->classroom_id,
                    'coach_id' => Auth::guard('coach')->id(),
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
        try {
            $result = DB::transaction(function () use ($request, $id) {
                $session = Session::firstOrCreate([
                    'name' => $request->session_id,
                    'classroom_id' => $request->classroom_id,
                ]);

                $theory = Theory::find($id);

                if (isset($request->file)) {
                    $theory->update([
                        'url' => $request->file
                    ]);
                }

                $theory->update([
                    'session_id' => $session->id,
                    'name' => $request->name,
                    'is_premium' => isset($request->is_premium) ? true : false,
                    'is_video' => isset($request->is_video) ? true : false,
                    'description' => $request->description,
                    'price' => $request->price,
                    'upload_date' => date('Y-m-d'),
                ]);

                return $theory;
            });

            return response([
                "data"      => $result,
                "message"   => 'Successfully updated!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $result = Theory::find($id);

            DB::transaction(function () use ($result) {
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

    public function theory_file(Request $request)
    {
        try {
            $path = Storage::disk('s3')->put('media', $request->file);
            $url = Storage::disk('s3')->url('/');

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

    public function theory_file_delete($id)
    {
        try {
            $result = TemporaryMedia::find($id);

            DB::transaction(function () use ($result) {
                Storage::disk('s3')->delete($result->path);
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

    public function theory_list($classroom_id, $session_id)
    {
        try {
            date_default_timezone_set("Asia/Jakarta");

            $path = Storage::disk('s3')->url('/');

            $theory = DB::table('theories')
                ->select(
                    'theories.*',
                    'classrooms.name as classrooms_name',
                    'coaches.name as coaches_name',
                    DB::raw("CONCAT('{$path}',theories.url) as file_url"),
                )
                ->join('sessions', 'sessions.id', 'theories.session_id')
                ->join('classrooms', 'classrooms.id', 'sessions.classroom_id')
                ->join('coach_classrooms', 'coach_classrooms.classroom_id', 'classrooms.id')
                ->join('coaches', 'coaches.id', 'coach_classrooms.coach_id')
                ->where([
                    ['classrooms.id', $classroom_id],
                    ['sessions.name', $session_id],
                    ['coaches.id', Auth::guard('coach')->id()],
                ])
                ->get();
            $classroom = Classroom::find($classroom_id);

            $result = [
                'theory' => $theory,
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

    public function download($path)
    {
        try {
            $result = Storage::disk('s3')->download('/'.$path);           

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
}

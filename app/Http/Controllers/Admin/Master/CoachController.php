<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Coach;
use Illuminate\Support\Facades\Hash;
use App\Models\CoachSosmed;
use App\Models\CoachClassroom;
use Storage;

use DataTables;

use App\Http\Controllers\BaseMenu;

class CoachController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Master'
            ],
            [
                'title' => 'Coach'
            ],
        ];

        return view('admin.master.coach.index', [
            'title' => 'Coach',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $path = Storage::disk('s3')->url('/');

        $coach_classroom = DB::table('coach_classrooms')
            ->select([
                'coach_classrooms.coach_id',
                DB::raw("count(coach_classrooms.coach_id) as total_class"),
            ])
            ->whereNull('coach_classrooms.deleted_at')
            ->groupBy('coach_classrooms.coach_id');

        $data = DB::table('coaches')
            ->select([
                'coaches.*',
                DB::raw("CONCAT('{$path}',coaches.image) as image_url"),
                'coach_classrooms.total_class',
                DB::raw("(
                    CASE
                        WHEN coach_classrooms.total_class IS NOT NULL THEN
                            coach_classrooms.total_class
                        ELSE
                            0
                    END
                ) as total_class"),
                'expertises.name as expertise_name',
            ])
            ->leftJoinSub($coach_classroom, 'coach_classrooms', function ($join) {
                $join->on('coaches.id', '=', 'coach_classrooms.coach_id');
            })
            ->leftJoin('expertises','coaches.expertise_id','=','expertises.id')
            ->whereNull([
                'coaches.deleted_at'
            ])
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        try {
            $result = DB::transaction(function () use($request){
                $validated = $request->validate([
                    'password' => 'required|confirmed|min:6',
                ]);
                if(!empty($request->profile_avatar)){
                    $file = $request->file('profile_avatar');
                    $path = Storage::disk('s3')->put('media', $file);
                }
                $result = Coach::create([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'description' => $request->profil_description,
                    'expertise_id' => $request->expertise,
                    'image' => $path,
                ]);

                if(!empty($request->sosmed)){
                    foreach ($request->sosmed as $key => $value) {
                        CoachSosmed::create([
                            'coach_id' => $result->id,
                            'url' => $request->url_sosmed[$key],
                            'sosmed_id' => $value
                        ]);
                    }
                }

                return $result;
            });

            return response([
                "data"      => $result,
                "message"   => 'Successfully saved!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $result = DB::transaction(function () use($request, $id){

                $coach = Coach::find($id);
                $update = [];
                if(!empty($request->profile_avatar)){
                    $file = $request->file('profile_avatar');
                    $path = Storage::disk('s3')->put('media', $file);
                    $update = [
                        'name' => $request->name,
                        'username' => $request->username,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'description' => $request->profil_description,
                        'expertise_id' => $request->expertise,
                        'image' => $path,
                    ];
                }else{
                    $update = [
                        'name' => $request->name,
                        'username' => $request->username,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'description' => $request->profil_description,
                        'expertise_id' => $request->expertise,
                    ];
                }
                $coach->update($update);
                if(!empty($request->init_sosmed_coach)){
                    foreach ($request->init_sosmed_coach as $key => $value) {
                        CoachSosmed::UpdateOrCreate(
                            ['id'=>$value],
                            [
                                'sosmed_id' => $request->sosmed[$key],
                                'coach_id' => $id,
                                'url' => $request->url_sosmed[$key]
                            ]
                        );
                    }
                }
                return $coach;
            });

            return response([
                "data"      => $result,
                "message"   => 'Successfully updated!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $result = Coach::find($id);

            DB::transaction(function () use($result){
                $coach_sosmeds = DB::table('coach_sosmeds')
                    ->where('coach_id',$result->id)
                    ->delete();

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
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function coach_sosmed($coach_id)
    {
        try {
            $result = CoachSosmed::where('coach_id',$coach_id)->get();

            return response([
                "message"   => 'OK!',
                "data" => $result
            ], 200);

        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function delete_medsos($id)
    {
        try {
            $result = CoachSosmed::find($id);

            DB::transaction(function () use($result){
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
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_permission($id)
    {
        try {
            $admin = Coach::find($id);
            $permissions = $admin->getAllPermissions();

            return response([
                "status"=>200,
                "data"=> $permissions,
                "message"=> 'Izin Ditemukan'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function set_permission(Request $request, $id)
    {
        try {
            $admin = Coach::find($id);
            $admin->syncPermissions($request->permissions);

            return response([
                "status"=>200,
                "data"  => "OK",
                "message"=> 'Izin Behasil Disesuaikan'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_class($id)
    {
        try {
            $result = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.*',
                    'classrooms.package_type',
                    'classrooms.name as classroom_name',
                ])
                ->leftJoin('classrooms','coach_classrooms.classroom_id','=','classrooms.id')
                ->where('coach_id', $id)
                ->whereNull('coach_classrooms.deleted_at')
                ->get();

            return response([
                "status"=>200,
                "data"  => $result,
                "message"=> 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function config(Request $request, $id)
    {
        try {
            // $data = \App\Models\ClassroomCategory::where('id', '7a9bc86c-0fb1-4635-8290-0e8231e9a67b')->withTrashed()->first();
            // return $data->{'sub_classroom_categories'}()->where('deleted_at', $data->deleted_at)->withTrashed()->update([
            //     'deleted_at' => null
            // ]);

            $result = CoachClassroom::where('coach_id', $id)
            ->whereNotIn('classroom_id',$request->select ? $request->select : [])
            ->delete();

            if(!empty($request->select)){
                foreach ($request->select as $key => $value) {
                    $coach_classroom = DB::table('coach_classrooms')
                        ->where('coach_id',$id)
                        ->where('classroom_id',$value)
                        ->first();

                    if(!empty($coach_classroom) && $coach_classroom->deleted_at != null){
                        $coach_schedule = DB::table('coach_schedules')
                            ->where('coach_schedules.coach_classroom_id',$coach_classroom->id)
                            ->whereDate('coach_schedules.deleted_at',$coach_classroom->deleted_at)
                            ->whereRaw("(SELECT EXTRACT(HOUR FROM coach_schedules.deleted_at)) = (SELECT EXTRACT(HOUR FROM TIMESTAMP '$coach_classroom->deleted_at'))")
                            ->whereRaw("(SELECT EXTRACT(MINUTE FROM coach_schedules.deleted_at)) = (SELECT EXTRACT(MINUTE FROM TIMESTAMP '$coach_classroom->deleted_at'))")
                            ->get();

                            foreach ($coach_schedule as $index => $data) {
                                $update_student_schedule = DB::table('student_schedules')
                                    ->where('coach_schedule_id',$data->id)
                                    ->whereDate('coach_schedules.deleted_at',$coach_classroom->deleted_at)
                                    ->whereRaw("(SELECT EXTRACT(HOUR FROM student_schedules.deleted_at)) = (SELECT EXTRACT(HOUR FROM TIMESTAMP '$coach_classroom->deleted_at'))")
                                    ->whereRaw("(SELECT EXTRACT(MINUTE FROM student_schedules.deleted_at)) = (SELECT EXTRACT(MINUTE FROM TIMESTAMP '$coach_classroom->deleted_at'))")
                                    ->update([
                                        'deleted_at'=>null
                                    ]);

                                $update_coach_schedule = DB::table('coach_schedules')
                                    ->where('coach_schedules.coach_classroom_id',$coach_classroom->id)
                                    ->whereDate('coach_schedules.deleted_at',$coach_classroom->deleted_at)
                                    ->whereRaw("(SELECT EXTRACT(HOUR FROM coach_schedules.deleted_at)) = (SELECT EXTRACT(HOUR FROM TIMESTAMP '$coach_classroom->deleted_at'))")
                                    ->whereRaw("(SELECT EXTRACT(MINUTE FROM coach_schedules.deleted_at)) = (SELECT EXTRACT(MINUTE FROM TIMESTAMP '$coach_classroom->deleted_at'))")
                                    ->update([
                                        'deleted_at'=>null
                                    ]);
                            }

                        $update_coach_classroom = DB::table('coach_classrooms')
                            ->where('coach_id',$id)
                            ->where('classroom_id',$value)
                            ->update([
                                'deleted_at' => null
                            ]);
                    }
                    elseif(empty($coach_classroom)){
                        $create = CoachClassroom::create([
                            'coach_id' => $id,
                            'classroom_id' => $value,
                        ]);
                    }
                }

            }
            return response([
                "status"=>200,
                "data"  => $result,
                "message"=> 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function activate_suspend($id)
    {
        try {
            $result = Coach::find($id)->update([
                'suspend' => true
            ]);

            return response([
                "status"=>200,
                "data"  => $result,
                "message"=> 'Successfully Activate'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function suspend($id)
    {
        try {
            $result = Coach::find($id)->update([
                'suspend' => false
            ]);

            return response([
                "status"=>200,
                "data"  => $result,
                "message"=> 'Successfully Suspend'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message"=> $e->getMessage(),
            ]);
        }
    }

    // view calendar

    public function view_calendar($id)
    {
        $navigation = [
            [
                'title' => 'Master'
            ],
            [
                'title' => 'Coach'
            ],
            [
                'title' => 'Calendar'
            ],
        ];
        $path = Storage::disk('s3')->url('/');
        $data = DB::table('coaches')
            ->select([
                'coaches.*',
                DB::raw("CONCAT('{$path}',coaches.image) as image_url"),
                'expertises.name as expertise_name',
            ])
            ->leftJoin('expertises','coaches.expertise_id','=','expertises.id')
            ->where('coaches.id',$id)
            ->first();

        return view('admin.master.coach-calendar.index', [
            'title' => 'Calendar',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
            'data' => $data
        ]);
    }
}

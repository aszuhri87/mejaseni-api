<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\BaseMenu;
use App\Models\Expertise;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Storage;
use DataTables;
use Exception;
use Illuminate\Support\Facades\Hash;

class StudentController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Master'
            ],
            [
                'title' => 'Student'
            ],
        ];

        $expertise = DB::table('expertises')->pluck('name', 'id');

        return view('admin.master.student.index', [
            'title' => 'Student',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
            'expertise' => $expertise,
        ]);
    }

    public function dt()
    {
        $path = Storage::disk('s3')->url('/');

        $data = DB::table('students')
            ->join('student_classrooms', 'student_classrooms.student_id', 'students.id')
            ->select([
                'students.*',
                DB::raw("CONCAT('{$path}',students.image) as image_url"),
                DB::raw("COUNT(student_classrooms.id) AS class"),
            ])
            ->whereNull([
                'students.deleted_at'
            ])
            ->groupBy('students.id')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function store(Request $request)
    {
        try {
            $result = DB::transaction(function () use ($request) {
                $validated = $request->validate([
                    'password' => 'required|confirmed|min:6',
                ]);
                if (!empty($request->profile_avatar)) {
                    $file = $request->file('profile_avatar');
                    $path = Storage::disk('s3')->put('media', $file);
                }
                $expertise = Expertise::find($request->expertise);
                $result = Student::create([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'active' => true,
                    'verified' => true,
                    'description' => $request->profil_description,
                    'expertise' => $expertise->name,
                    'image' => $path,
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

                $student = Student::find($id);
                $update = [];
                if (!empty($request->profile_avatar)) {
                    $file = $request->file('profile_avatar');
                    $path = Storage::disk('s3')->put('media', $file);
                    $update = [
                        'name' => $request->name,
                        'username' => $request->username,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'description' => $request->profil_description,
                        'expertise' => $request->expertise,
                        'image' => $path,
                    ];
                } else {
                    $update = [
                        'name' => $request->name,
                        'username' => $request->username,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'description' => $request->profil_description,
                        'expertise' => $request->expertise,
                    ];
                }
                $student->update($update);

                return $student;
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
            $result = Student::find($id);

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

    public function get_permission($id)
    {
        try {
            $admin = Student::find($id);
            $permissions = $admin->getAllPermissions();

            return response([
                "status" => 200,
                "data" => $permissions,
                "message" => 'Izin Ditemukan'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function set_permission(Request $request, $id)
    {
        try {
            $admin = Student::find($id);
            $admin->syncPermissions($request->permissions);

            return response([
                "status" => 200,
                "data"  => "OK",
                "message" => 'Izin Behasil Disesuaikan'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function verified(Request $request, $id)
    {

        try {
            $result = DB::transaction(function () use ($request, $id) {

                $student = Student::find($id);
                $update = [
                    'verified' => $request->verified,
                ];
                $student->update($update);

                return $student;
            });

            return response([
                "data"      => $result,
                "status"      => 200,
                "message"   => 'Successfully updated!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }
    public function actived(Request $request, $id)
    {

        try {
            $result = DB::transaction(function () use ($request, $id) {

                $student = Student::find($id);
                $update = [
                    'actived' => $request->actived,
                ];
                $student->update($update);

                return $student;
            });

            return response([
                "data"      => $result,
                "status"      => 200,
                "message"   => 'Successfully updated!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }
}

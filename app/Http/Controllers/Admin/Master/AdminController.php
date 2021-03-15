<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\BaseMenu;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Classroom;
use App\Models\Tools;
use App\Models\ClassroomTools;

use DataTables;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Storage;

class AdminController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Master'
            ],
            [
                'title' => 'Admin'
            ],
        ];
        $role = Role::where('guard_name', 'admin')->pluck('name', 'id');
        return view('admin.master.admin.index', [
            'title' => 'Admin',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
            'role' => $role,
        ]);
    }

    public function dt()
    {
        $data = DB::table('admins')
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', 'admins.id')
            ->leftJoin('roles', 'roles.id', 'model_has_roles.role_id')
            ->select([
                'admins.*',
                'roles.id as role_id',
                DB::raw("CASE WHEN roles.id IS NOT NULL THEN 'Super Admin' ELSE 'Admin' END admin_type")
            ])
            ->whereNull([
                'admins.deleted_at'
            ])
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function store(Request $request)
    {
        try {
            if($request->password != $request->c_password){
                return response([
                    "status" => 400,
                    "message" => 'Konfirmasi Password Salah'
                ], 400);
            }

            $result = DB::transaction(function () use ($request) {

                $admin = Admin::create([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                if (isset($request->super_admin)) {
                    $admin->syncRoles('Super Admin');
                }

                return $admin;
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
            if($request->password != $request->c_password){
                return response([
                    "status" => 400,
                    "message" => 'Konfirmasi Password Salah'
                ], 400);
            }

            $result = DB::transaction(function () use ($request, $id) {
                $result = Admin::find($id)->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                ]);

                if (!empty($request->change_password)) {
                    $result = Admin::find($id)->update([
                        'password' => Hash::make($request->password),
                    ]);
                }

                $admin = Admin::find($id);

                if (isset($request->super_admin)) {
                    $admin->syncRoles('Super Admin');
                }else{
                    $admin->removeRole('Super Admin');
                }

                return $result;
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
            $result = Admin::find($id);

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
            $admin = Admin::find($id);
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

            $admin = Admin::find($id);
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
}

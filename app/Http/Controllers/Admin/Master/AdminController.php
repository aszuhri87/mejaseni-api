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
        $role = Role::pluck('name', 'id');
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
            ->select([
                'admins.*',
            ])
            ->whereNull([
                'admins.deleted_at'
            ])
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('tipe_admin', function ($data) {
                $user = Admin::find($data->id);
                return !empty($user->getRoleNames()) ? $user->getRoleNames()->first() : '-';
            })
            ->addColumn('role_id', function ($data) {
                $user = Admin::find($data->id);
                $role = Role::where('name',$user->getRoleNames()->first())->first();
                return !empty($role->id) ? $role->id : '-';
            })
            ->make(true);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        try {
            $result = DB::transaction(function () use ($request) {

                $admin = Admin::create([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                $role = Role::whereIn('id', $request->role)->get();

                $admin->assignRole($role);

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


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
    }


    public function update(Request $request, $id)
    {
        try {
            $result = DB::transaction(function () use ($request, $id) {
                $result = Admin::find($id)->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                $result = Admin::find($id);
                
                $role = Role::whereIn('id', $request->role)->get();
                
                $result->syncRoles($role);

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

            $admin = Admin::find($id);
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
}

<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Classroom;
use App\Models\Tools;

use DataTables;
use Storage;

class ClassroomController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Master'
            ],
            [
                'title' => 'Courses'
            ],
            [
                'title' => 'Class'
            ],
        ];

        return view('admin.master.classroom.index', [
            'title' => 'Class',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $data = DB::table('packages')
            ->select([
                'id',
                'name',
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        try {
            $result = DB::transaction(function () use($request){
                $result = Package::create([
                    'name' => $request->name,
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
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $result = DB::transaction(function () use($request, $id){
                $result = Package::find($id)->update([
                    'name' => $request->name,
                ]);

                return $result;
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
            $result = Package::find($id);

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

    public function ac(Request $request)
    {
        $data = Tools::select([
                'id',
                'text'
            ])
            ->where('text', 'ilike', '%' . strtoupper($request->param) . '%')
            ->orderBy('text', 'asc')
            ->get();

        return response([
            'data' => $data,
        ]);
    }
}

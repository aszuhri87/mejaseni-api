<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\ClassroomCategory;

use DataTables;

class ClassroomCategoryController extends BaseMenu
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
                'title' => 'Class Category'
            ],
        ];

        return view('admin.master.classroom-category.index', [
            'title' => 'Class Category',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $data = DB::table('classroom_categories')
            ->select([
                'id',
                'name',
                'profile_coach_video_id'
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
                $result = ClassroomCategory::create([
                    'name' => $request->name,
                    'profile_coach_video_id' => $request->profile_coach_video_id
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
                $result = ClassroomCategory::find($id)->update([
                    'name' => $request->name,
                    'profile_coach_video_id' => $request->profile_coach_video_id
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
            $result = ClassroomCategory::find($id);

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
}

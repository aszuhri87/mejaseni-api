<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\SubClassroomCategory;

use DataTables;
use Storage;

class SubClassroomCategoryController extends BaseMenu
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
                'title' => 'Sub Class Category'
            ],
        ];

        return view('admin.master.sub-classroom-category.index', [
            'title' => 'Sub Class Category',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $path = asset('storage').'/';

        $data = DB::table('sub_classroom_categories')
            ->select([
                'sub_classroom_categories.id',
                'sub_classroom_categories.name',
                'classroom_categories.name as category',
                'sub_classroom_categories.classroom_category_id',
                'sub_classroom_categories.profile_coach_video_id',
                DB::raw("CONCAT('{$path}',sub_classroom_categories.image) as image_url"),
            ])
            ->leftJoin('classroom_categories', 'classroom_categories.id', '=', 'sub_classroom_categories.classroom_category_id')
            ->whereNull([
                'sub_classroom_categories.deleted_at'
            ])
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        try {
            if(!isset($request->image)){
                return response([
                    'message' => 'Please input image'
                ], 400);
            }

            $result = DB::transaction(function () use($request){
                if(isset($request->image)){
                    $file = $request->file('image');
                    $path = Storage::disk('public')->put('images', $file);
                }

                $result = SubClassroomCategory::create([
                    'classroom_category_id' => $request->classroom_category_id,
                    'profile_coach_video_id' => $request->profile_coach_video_id,
                    'name' => $request->name,
                    'image' => $path
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
                $result = SubClassroomCategory::find($id);

                if(isset($request->image)){
                    $file = $request->file('image');
                    $path = Storage::disk('public')->put('images', $file);

                    $result->image = $path;
                    $result->update();
                }

                $result->update([
                    'classroom_category_id' => $request->classroom_category_id,
                    'profile_coach_video_id' => $request->profile_coach_video_id,
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
            $result = SubClassroomCategory::find($id);

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

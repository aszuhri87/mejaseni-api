<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\BaseMenu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ClassroomCategory;

use DataTables;
use Storage;
use DB;

class ClassController extends BaseMenu
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
                'title' => 'Master'
            ],
            [
                'title' => 'Courses'
            ],
            [
                'title' => 'Classroom'
            ],
        ];

        return view('admin.cms.classroom.index', [
            'title' => 'Classroom',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $path = Storage::disk('s3')->url('/');
        $data = DB::table('classroom_categories')
            ->select([
                'classroom_categories.id',
                'classroom_categories.name as category',
                'classrooms.name as classroom'
            ])
            ->leftJoin('classrooms','classrooms.id','=','classroom_categories.classroom_id')
            ->whereNull([
                'classrooms.deleted_at',
                'classroom_categories.deleted_at'
            ])
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function get_classrooms($category_id)
    {

        try {
            $regular_class_type = 2;
            $classrooms = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name'
                ])
                ->leftJoin('classroom_categories','classrooms.classroom_category_id','=','classroom_categories.id')
                ->whereNull([
                    'classrooms.deleted_at',
                    'classroom_categories.deleted_at'
                ])
                ->whereNotNull('classrooms.sub_classroom_category_id')
                ->where('classrooms.classroom_category_id', $category_id)
                ->where('classrooms.package_type', $regular_class_type)
                ->get();

            return response([
                "data"      => $classrooms,
                "message"   => 'Successfully!'
            ], 200);

        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $result = DB::transaction(function () use($request, $id){
                $result = ClassroomCategory::find($id)->update([
                   'classroom_id' => $request->classroom_id
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

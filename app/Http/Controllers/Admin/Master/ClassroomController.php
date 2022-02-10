<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Classroom;
use App\Models\ClassroomTools;
use App\Models\Tools;
use App\Models\Session;

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
        $path = Storage::disk('s3')->url('/');

        $data = DB::table('classrooms')
            ->select([
                'classrooms.*',
                'classroom_categories.name as category',
                'sub_classroom_categories.name as sub_category',
                DB::raw("CONCAT('{$path}',classrooms.image) as image_url"),
            ])
            ->leftJoin('classroom_categories','classroom_categories.id','=','classrooms.classroom_category_id')
            ->leftJoin('sub_classroom_categories','sub_classroom_categories.id','=','classrooms.sub_classroom_category_id')
            ->whereNull([
                'classrooms.deleted_at'
            ])
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        try {
            if(!isset($request->image)){
                return response([
                    "message"   => 'Gambar harus diisi!'
                ], 400);
            }

            $result = DB::transaction(function () use($request){
                if(isset($request->image)){
                    $file = $request->file('image');
                    $path = Storage::disk('s3')->put('media', $file);
                }

                $classroom = Classroom::create([
                    'classroom_category_id' => $request->classroom_category_id,
                    'sub_classroom_category_id' => $request->sub_classroom_category_id,
                    'package_type' => $request->package_type,
                    'sub_package_type' => $request->sub_package_type,
                    'name' => $request->name,
                    'type' => 1,
                    'session_total' => $request->session,
                    'session_duration' => $request->duration,
                    'price' => $request->price,
                    'description' => $request->description,
                    'image' => $path,
                    'buy_btn_disable' => isset($request->buy_btn_disable) ? true : false,
                    'hide' => isset($request->hide) ? true : false,
                    'home_course_available' => isset($request->home_course_available) ? true : false,
                ]);

                if(isset($request->tools)){
                    foreach ($request->tools as $key => $tools) {
                        $data_tools = Tools::firstOrCreate([
                            'text' => $tools
                        ]);

                        ClassroomTools::create([
                            'classroom_id' => $classroom->id,
                            'tool_id' => $data_tools->id,
                        ]);
                    }
                }

                for ($i=0; $i < (int)$request->session; $i++) {
                    Session::create([
                        'classroom_id' => $classroom->id,
                        'name' => $i + 1
                    ]);
                }

                return $classroom;
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
            $sub_category_check = DB::table('sub_classroom_categories')
                ->select([
                    'id',
                    'name'
                ])
                ->where('classroom_category_id', $request->classroom_category_id)
                ->whereNull('deleted_at')
                ->count();

            if($sub_category_check > 0 && !isset($request->sub_classroom_category_id)){
                return response([
                    "message"   => 'Sub Kategori harus diisi!'
                ], 400);
            }

            $result = DB::transaction(function () use($request, $id, $sub_category_check){
                $classroom = Classroom::find($id);

                $classroom->update([
                    'classroom_category_id' => $request->classroom_category_id,
                    'sub_classroom_category_id' => $sub_category_check > 0 ? $request->sub_classroom_category_id : null,
                    'package_type' => $request->package_type,
                    'sub_package_type' => $request->package_type == 2 && isset($request->switch_sub) ? $request->sub_package_type : null,
                    'name' => $request->name,
                    'type' => 1,
                    'session_total' => $request->session,
                    'session_duration' => $request->duration,
                    'price' => $request->price,
                    'price_home_course' => $request->price_home_course,
                    'description' => $request->description,
                    'buy_btn_disable' => isset($request->buy_btn_disable) ? true : false,
                    'hide' => isset($request->hide) ? true : false,
                    'home_course_available' => isset($request->home_course_available) ? true : false,
                ]);

                if(isset($request->image)){
                    $file = $request->file('image');
                    $path = Storage::disk('s3')->put('media', $file);

                    $classroom->image = $path;
                    $classroom->update();
                }

                if(isset($request->tools)){
                    foreach ($request->tools as $key => $tools) {
                        $data_tools = Tools::firstOrCreate([
                            'text' => $tools
                        ]);

                        ClassroomTools::create([
                            'classroom_id' => $classroom->id,
                            'tool_id' => $data_tools->id,
                        ]);
                    }
                }

                $session = Session::where('classroom_id',$classroom->id)->get();

                if(count($session) > $request->session){
                    Session::where('classroom_id',$classroom->id)
                        ->whereRaw("name::integer > $request->session")
                        ->delete();
                }else{
                    $diff = $request->session - count($session);
                    for ($i=0; $i < $diff; $i++) {
                        Session::create([
                            'classroom_id' => $classroom->id,
                            'name' => count($session) + ($i + 1)
                        ]);
                    }
                }

                return $classroom;
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
            $result = Classroom::find($id);

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

    public function get_tools($id)
    {
        $data = DB::table('classroom_tools')
            ->select([
                'classroom_tools.id',
                'classroom_tools.classroom_id',
                'tools.text'
            ])
            ->leftJoin('tools','classroom_tools.tool_id','=','tools.id')
            ->where('classroom_tools.classroom_id', $id)
            ->whereNull('classroom_tools.deleted_at')
            ->get();

        return response([
            'data' => $data,
        ]);
    }

    public function delete_tools($id)
    {
        $data = ClassroomTools::find($id)->delete();

        return response([
            'data' => $data,
        ]);
    }
}

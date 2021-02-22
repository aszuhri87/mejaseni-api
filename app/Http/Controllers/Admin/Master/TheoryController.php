<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Theory;
use App\Models\Session;
use App\Models\TemporaryMedia;

use DataTables;
use Storage;

class TheoryController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Master'
            ],
            [
                'title' => 'Materi'
            ],
        ];

        return view('admin.master.theory.index', [
            'title' => 'Materi',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $path = Storage::disk('s3')->url('/');

        $data = DB::table('theories')
            ->select([
                'theories.id',
                'theories.session_id',
                'theories.name',
                'theories.is_premium',
                'theories.is_video',
                'theories.url',
                'theories.description',
                'theories.price',
                'theories.confirmed',
                'theories.upload_date',
                'sessions.name as session',
                DB::raw("CONCAT('{$path}',theories.url) as file_url"),
                'sessions.classroom_id',
                'classrooms.classroom_category_id',
                'classrooms.name as classroom',
                'classrooms.package_type',
                'classroom_categories.name as category',
                'sub_classroom_categories.name as sub_category',
                'classrooms.sub_classroom_category_id',
            ])
            ->leftJoin('sessions', 'sessions.id','=','theories.session_id')
            ->leftJoin('classrooms', 'classrooms.id','=','sessions.classroom_id')
            ->leftJoin('classroom_categories', 'classroom_categories.id','=','classrooms.classroom_category_id')
            ->leftJoin('sub_classroom_categories', 'sub_classroom_categories.id','=','classrooms.sub_classroom_category_id')
            ->whereNull([
                'theories.deleted_at'
            ])
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        try {
            if(!isset($request->file)){
                return response([
                    "message"   => 'File harus diisi!'
                ], 400);
            }

            $result = DB::transaction(function () use($request){
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
                $session = Session::firstOrCreate([
                    'name' => $request->session_id,
                    'classroom_id' => $request->classroom_id,
                ]);

                $theory = Theory::find($id);

                if(isset($request->file)){
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
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $result = Theory::find($id);

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

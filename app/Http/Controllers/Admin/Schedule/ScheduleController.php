<?php

namespace App\Http\Controllers\Admin\Schedule;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ScheduleController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Schedule'
            ],
        ];

        return view('admin.schedule.index', [
            'title' => 'Schedule',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function store(Request $request)
    {
        try {
            dd($request->all());
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
}

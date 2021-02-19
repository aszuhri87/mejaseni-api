<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Storage;

use App\Models\Coach;
use App\Models\CoachSosmed;
use App\Models\CoachClassroom;
use App\Models\CoachSchedule;

use DataTables;
use Auth;

use App\Http\Controllers\BaseMenu;

class CoachListController extends BaseMenu
{
    public function index($id)
    {
        $navigation = [
            [
                'title' => 'Master'
            ],
            [
                'title' => 'Coach'
            ],
            [
                'title' => 'View List'
            ],
        ];

        $path = Storage::disk('s3')->url('/');
        $data = DB::table('coaches')
            ->select([
                'coaches.*',
                DB::raw("CONCAT('{$path}',coaches.image) as image_url"),
                'expertises.name as expertise_name',
            ])
            ->leftJoin('expertises','coaches.expertise_id','=','expertises.id')
            ->where('coaches.id',$id)
            ->first();

        return view('admin.master.coach-list.index', [
            'title' => 'View List',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
            'data' => $data,
        ]);
    }

    public function get_class($id)
    {
        try {
            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name'
                ]);

            $result = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.id',
                    'coach_classrooms.coach_id',
                    'coach_classrooms.classroom_id',
                    'classrooms.name',
                ])
                ->joinSub($classroom, 'classrooms', function ($join) {
                    $join->on('coach_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->where('coach_id',$id)
                ->get();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            // dd($request->all());
            $result = DB::transaction(function () use($request) {
                $insert=null;
                $date = date('Y-m-d', strtotime($request->date));
                $time = date('H:i:s', strtotime($request->time));
                $datetime = date('Y-m-d H:i:s',strtotime($date . ' ' . $time));
                if($request->class_type == 1){
                    $insert = CoachSchedule::create([
                        'coach_classroom_id' => $request->class,
                        'platform_id' => $request->platform,
                        'admin_id' => Auth::guard('admin')->user()->id,
                        'accepted' => true,
                        'datetime' => $datetime,
                    ]);
                }
                else{

                }

                return $insert;

            });

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseMenu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Event;

use DB;
use DataTables;
use Storage;

class EventController extends BaseMenu
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
                'title' => 'Event'
            ],
        ];

        return view('admin.event.index', [
            'title' => 'Event',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $path = Storage::disk('s3')->url('/');

        $carts = DB::table('carts')
            ->select([
                'carts.event_id',
                DB::raw("COUNT('carts.event_id') as count_participants")
            ])
            ->groupBy("carts.event_id")
            ->whereNull("carts.deleted_at");

        $data = DB::table('events')
            ->select([
                'events.*',
                DB::raw("CONCAT('{$path}',image) as image_url"),
                DB::raw("CASE
                    WHEN carts.count_participants IS NULL THEN 0
                    ELSE carts.count_participants
                END count_participants")
            ])
            ->leftJoinSub($carts, 'carts', function($join){
                $join->on("events.id", "carts.event_id");
            })
            ->whereNull([
                'events.deleted_at'
            ])
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }


    public function participants_dt($id)
    {
        $path = Storage::disk('s3')->url('/');

        $data = DB::table('students')
            ->select([
                'students.*',
                'carts.id as cart_id',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->leftJoin('carts','carts.student_id','=','students.id')
            ->where('carts.event_id',$id)
            ->whereNull([
                'students.deleted_at'
            ])
            ->whereNull([
                'carts.deleted_at'
            ])
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
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

                $result = Event::create([
                    'classroom_category_id' => $request->classroom_category_id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'image' => $path,
                    'is_free' => $request->is_free ? $request->is_free:false,
                    'total' => $request->total ? $request->total:0,
                    'quota' => $request->quota,
                    'location' => $request->location,
                    'start_at' => $request->start_at,
                    'end_at' => $request->end_at,
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
                $result = Event::find($id);
                $path = null;

                if(isset($request->image)){
                    $file = $request->file('image');
                    $path = Storage::disk('s3')->put('media', $file);
                }

                $result->update([
                    'classroom_category_id' => $request->classroom_category_id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'image' => $path ? $path:$result->image,
                    'is_free' => $request->is_free ? $request->is_free:false,
                    'total' => $request->total ? $request->total:0,
                    'quota' => $request->quota,
                    'location' => $request->location,
                    'start_at' => $request->start_at,
                    'end_at' => $request->end_at,
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
        try {
            $result = Event::find($id);

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

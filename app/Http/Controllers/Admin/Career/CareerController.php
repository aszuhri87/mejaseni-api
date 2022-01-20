<?php

namespace App\Http\Controllers\Admin\Career;

use App\Http\Controllers\BaseMenu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Career;

use DB;
use DataTables;
use Storage;

class CareerController extends BaseMenu
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
                'title' => 'Career'
            ],
        ];

        return view('admin.career.index', [
            'title' => 'Career',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $data = DB::table('careers')
            ->select([
                'careers.*',
            ])
            ->whereNull([
                'careers.deleted_at'
            ])
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
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
            $result = DB::transaction(function () use($request){
                $result = Career::create([
                    'type' => $request->type,
                    'title' => $request->title,
                    'placement' => $request->placement,
                    'is_closed' => $request->is_closed ? false:true
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
                $result = Career::find($id)->update([
                    'type' => $request->type,
                    'title' => $request->title,
                    'placement' => $request->placement,
                    'is_closed' => $request->is_closed ? false:true
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
            $result = Career::find($id);

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = Career::find($id);
        session()->put('career_id', $id);

        $navigation = [
            [
                'title' => 'Career'
            ],
            [
                'title' => $result->title
            ],
        ];

        return view('admin.career-detail.index', [
            'title' => $result->title,
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt_detail()
    {
        $data = DB::table('career_collections')
            ->select([
                'career_collections.*',
            ])
            ->whereNull([
                'career_collections.deleted_at'
            ])
            ->where('career_collections.career_id', session()->get('career_id'))
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function show_detail($id)
    {
        try {
            $path = Storage::disk('s3')->url('/');

            $result = DB::table('career_media_collections')
                ->select([
                    'career_media_collections.*',
                    DB::raw("CONCAT('{$path}',url) as file_url"),
                ])
                ->where('career_collection_id', $id)
                ->whereNull([
                    'deleted_at'
                ])
                ->get();

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

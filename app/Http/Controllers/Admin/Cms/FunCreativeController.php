<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\BaseMenu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FunCreative;


use DB;
use DataTables;
use Storage;

class FunCreativeController extends BaseMenu
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
                'title' => 'CMS'
            ],
            [
                'title' => 'Fun & Creative'
            ],
        ];

        return view('admin.cms.fun-creative.index', [
            'title' => 'Fun & Creative',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $path = Storage::disk('s3')->url('/');
        $data = DB::table('fun_creatives')
            ->select([
                'fun_creatives.*',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'fun_creatives.deleted_at'
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

                $result = FunCreative::create([
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
                $result = FunCreative::find($id);
                $path = null;

                if(isset($request->image)){
                    $file = $request->file('image');
                    $path = Storage::disk('s3')->put('media', $file);
                }

                $result->update([
                    'image' => $path ? $path:$result->image
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
            $result = FunCreative::find($id);

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

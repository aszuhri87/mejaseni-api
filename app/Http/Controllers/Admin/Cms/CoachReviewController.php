<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\BaseMenu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CoachReview;

use DB;
use DataTables;
use Storage;

class CoachReviewController extends BaseMenu
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
                'title' => 'Coach Review'
            ],
        ];

        return view('admin.cms.coach-review.index', [
            'title' => 'Coach Review',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $path = Storage::disk('s3')->url('/');
        $data = DB::table('coaches')
            ->select([
                'coaches.name',
                'coaches.description',
                'coach_reviews.id',
                DB::raw("CONCAT('{$path}',coaches.image) as image_url"),
            ])
            ->leftJoin('coach_reviews','coach_reviews.coach_id','=','coaches.id')
            ->whereNull([
                'coach_reviews.deleted_at'
            ])
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function coach_dt()
    {
        $path = Storage::disk('s3')->url('/');

        $coach_reviews = DB::table('coach_reviews')
                ->whereNull('deleted_at');

        $data = DB::table('coaches')
            ->select([
                'coaches.*',
                'coach_reviews.coach_id',
                DB::raw("CONCAT('{$path}',coaches.image) as image_url"),
            ])
            ->leftJoinSub($coach_reviews, 'coach_reviews', function($join){
                $join->on("coaches.id", "coach_reviews.coach_id");
            })
            ->whereNull([
                'coach_reviews.coach_id'
            ])
            ->whereNull([
                'coach_reviews.deleted_at'
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
    public function store(Request $request, $coach_id)
    {
        try {

            $result = DB::transaction(function () use($coach_id){
                $result = CoachReview::create([
                    'coach_id' => $coach_id,
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
        //
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
            $result = CoachReview::find($id);

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

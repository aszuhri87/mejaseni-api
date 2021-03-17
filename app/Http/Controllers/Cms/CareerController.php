<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;
use App\Models\CareerCollection;
use App\Models\CareerMediaCollection;

use DB;
use Storage;

class CareerController extends Controller
{
    public function index()
    {
        $company = Company::first();
        $branches = Branch::all();

        $internal_team = 1;
        $professional_coach = 2;

        $path = Storage::disk('s3')->url('/');

        $fun_creatives = DB::table('fun_creatives')
            ->select([
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->get();

    	$internal_team_careers = DB::table('careers')
            ->select([
                'id',
                'title',
                'placement',
                'type',
            ])
            ->where('is_closed',false)
            ->where('type',$internal_team)
            ->whereNull([
                'deleted_at'
            ])
            ->get();

        $professional_coach_careers = DB::table('careers')
            ->select([
                'id',
                'title',
                'placement',
                'type',
            ])
            ->where('is_closed',false)
            ->where('type',$professional_coach)
            ->whereNull([
                'deleted_at'
            ])
            ->get();

        $social_medias = DB::table('social_media')
            ->select([
                'url',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->get();

        return view('cms.career.index', [
            "company" => $company,
            "branches" => $branches,
            "fun_creatives" => $fun_creatives,
            'internal_team_careers' => $internal_team_careers,
            'professional_coach_careers' => $professional_coach_careers,
            "social_medias" => $social_medias
        ]);
    }

    public function store(Request $request)
    {
        try {
            if(!isset($request['files'])){
                return response([
                    "message"   => 'Lampiran kosong!'
                ], 400);
            }

            DB::transaction(function () use($request){
                $collection = CareerCollection::create([
                    'career_id' => $request->career_id,
                    'date' => date('Y-m-d H:i:s'),
                    'name' => $request->name,
                    'email' => $request->email,
                ]);

                if(isset($request['files'])){
                    foreach ($request['files'] as $key => $value) {
                        CareerMediaCollection::create([
                            'career_collection_id' => $collection->id,
                            'url' => $value,
                        ]);
                    }
                }
            });

            return response([
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

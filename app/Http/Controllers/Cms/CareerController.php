<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;

use DB;

class CareerController extends Controller
{
    public function index()
    {
    	$company = Company::first();
    	$branchs = Branch::all();

    	$internal_team = 1;
    	$profesional_coach = 2;

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
            ->where('type',$profesional_coach)
            ->whereNull([
                'deleted_at'
            ])
            ->get();

    	return view('cms.career.index', [
    			"company" => $company, 
    			"branchs" => $branchs, 
    			'internal_team_careers' => $internal_team_careers, 
    			'professional_coach_careers' => $professional_coach_careers
    		]);
    }
}

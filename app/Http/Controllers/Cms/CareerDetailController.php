<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;


use App\Models\Branch;
use App\Models\Company;
use App\Models\Career;

use DB;

class CareerDetailController extends Controller
{
    public function index($id)
    {
    	try {

    		// check if id valid
    		$is_valid = Uuid::isValid($id);
	    	if(!$is_valid)
	    		return abort(404);


	    	$career = Career::find($id);
	    	if(!$career)
	    		return abort(404);
	    	

	    	$company = Company::first();
	    	$branchs = Branch::all();
	    	$job_descriptions = DB::table('job_descriptions')
	    							->select([
	    								'id',
	    								'description'
	    							])
	    							->where('career_id',$id)
	    							->whereNull('deleted_at')
	    							->get();

	    	$job_requirements = DB::table('job_requirements')
	    							->select([
	    								'id',
	    								'description'
	    							])
	    							->where('career_id',$id)
	    							->whereNull('deleted_at')
	    							->get();



	    	return view('cms.career-detail.index', [
	    		"company" => $company, 
	    		"branchs" => $branchs,
	    		"job_requirements" => $job_requirements,
	    		"job_descriptions" => $job_descriptions,
	    		"career" => $career
	    	]);
    	} catch (Exception $e) {
    		return abort(500);
    	}
    }
}

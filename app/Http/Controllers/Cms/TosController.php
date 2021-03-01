<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;

class TosController extends Controller
{
    public function index()
    {
    	$company = Company::first();
    	$branchs = Branch::all();


    	return view('cms.tos.index', ["company" => $company, "branchs" => $branchs]);
    }
}

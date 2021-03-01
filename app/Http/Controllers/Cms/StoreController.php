<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;


use DB;


class StoreController extends Controller
{
    public function index()
    {
    	$company = Company::first();
    	$branchs = Branch::all();

    	$classroom_categories = DB::table('classroom_categories')
            ->select([
                'id',
                'name',
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->get();

    	return view('cms.store.index', ["company" => $company, "branchs" => $branchs, "classroom_categories" => $classroom_categories]);
    }
}

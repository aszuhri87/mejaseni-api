<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Branch;
use App\Models\Company;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
    	$company = Company::first();
    	$branchs = Branch::all();
    	$faqs = Faq::all();

    	return view('cms.faq.index', ["company" => $company, "branchs" => $branchs, "faqs" => $faqs]);
    }
}

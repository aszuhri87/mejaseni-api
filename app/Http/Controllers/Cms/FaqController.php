<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Branch;
use App\Models\Company;
use App\Models\Faq;

use DB;
use Storage;

class FaqController extends Controller
{
    public function index()
    {
        $company = Company::first();
        $branchs = Branch::all();
        $faqs = Faq::orderBy('number','asc')->get();

        $path = Storage::disk('s3')->url('/');
        $social_medias = DB::table('social_media')
            ->select([
                'url',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->get();

        return view('cms.faq.index', [
            "company" => $company,
            "branchs" => $branchs,
            "faqs" => $faqs,
            "social_medias" => $social_medias
        ]);
    }
}

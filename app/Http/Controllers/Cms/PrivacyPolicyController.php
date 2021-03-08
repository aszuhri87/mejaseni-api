<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Company;
use App\Models\PrivacyPolicy;

use DB;
use Storage;

class PrivacyPolicyController extends Controller
{
    public function index()
    {
    	$company = Company::first();
    	$branchs = Branch::all();

    	$privacy_policy = PrivacyPolicy::first();
        $path = Storage::disk('s3')->url('/');

        $privacy_policy_items = null;
    	if($privacy_policy){
            $privacy_policy_items = DB::table('privacy_policy_items')
                    ->select([
                        'title',
                        'description',
                    ])
                    ->where('privacy_policy_id',$privacy_policy->id)
                    ->whereNull('deleted_at')
                    ->get();
        }

        $social_medias = DB::table('social_media')
            ->select([
                'url',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->whereNull([
                'deleted_at'
            ])
            ->get();

    	return view('cms.privacy-policy.index', [
            "company" => $company, 
            "branchs" => $branchs,
            "privacy_policy" => $privacy_policy,
            "privacy_policy_items" => $privacy_policy_items,
            "social_medias" => $social_medias
        ]);
    }
}

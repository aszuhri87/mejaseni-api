<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseMenu;
use Illuminate\Validation\Rule;

use App\Models\BankAccount;
use Auth;

class BankAccountController extends BaseMenu
{
    public function index()
    {
        $bank_account = DB::table('bank_accounts')
            ->whereNull('deleted_at')
            ->where('coach_id',Auth::guard('coach')->user()->id)
            ->first();

        $navigation = [
            [
                'title' => 'Bank Account'
            ],
        ];

        return view('coach.bank_account.index', [
            'title' => 'Bank Account',
            'navigation' => $navigation,
            'list_menu' => $this->menu_coach(),
            'bank_account' => $bank_account
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            date_default_timezone_set("Asia/Jakarta");
            $validatedData = $request->validate([
                'bank' => [
                    'required',
                    'max:255'
                ],
                'bank_number' => [
                    'required',
                    Rule::unique(BankAccount::class, 'bank_number')
                        ->ignore($id)
                ],
                'name_account' => [
                    'required',
                    'string'
                ]
            ]);

            $result = DB::transaction(function () use ($request,$id){
                $update = BankAccount::updateOrCreate(
                    ['coach_id' => $id],
                    [
                        'bank' =>$request->bank,
                        'bank_number' => $request->bank_number,
                        'name_account' => $request->name_account,
                    ]
                );
                return $update;
            });

            return response([
                "status"    => 200,
                "data"      => $result,
                "message"   => 'Successfully updated!'
            ], 200);

        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message"=> $e->getMessage(),
            ]);
        }
    }
}

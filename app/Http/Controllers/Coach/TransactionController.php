<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\IncomeTransaction;

use Exception;
use DataTables;

class TransactionController extends BaseMenu
{
    public function withdraw()
    {
        $navigation = [
            [
                'title' => 'Coach'
            ],
            [
                'title' => 'Withdraw Detail'
            ],
        ];

        return view('coach.withdraw.index', [
            'title' => 'Withdraw Detail',
            'navigation' => $navigation,
            'list_menu' => $this->menu_coach(),
        ]);
    }

    public function withdraw_dt()
    {
        $path = Storage::disk('s3')->url('/');

        $data = DB::table('income_transactions')
            ->select([
                'id',
                'bank',
                'bank_number',
                'name_account',
                'status',
                'total',
                'datetime',
                DB::raw("CASE
                    WHEN status = 1
                        THEN 'Waiting'
                    WHEN status = 2
                        THEN 'Success'
                    ELSE 'Cancel'
                END status_text"),
                'image',
                DB::raw("CONCAT('{$path}',image) as image_url"),
            ])
            ->where('coach_id', Auth::guard('coach')->user()->id)
            ->whereNull([
                'deleted_at'
            ])
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function get_balance()
    {
        try {
            $amount = 0;
            $balance = 0;

            $incomes = DB::table('incomes')
                ->select([
                    DB::raw("SUM(amount) as amount")
                ])
                ->where('coach_id', Auth::guard('coach')->user()->id)
                ->whereNull('deleted_at')
                ->first();

            if($incomes){
                $income_transactions = DB::table('income_transactions')
                    ->select([
                        DB::raw("SUM(total) as balance")
                    ])
                    ->where('coach_id', Auth::guard('coach')->user()->id)
                    ->whereNull('deleted_at')
                    ->first();

                if($income_transactions){
                    $amount = (int)$incomes->amount - (int)$income_transactions->balance;
                    $balance = (int)$income_transactions->balance;
                }else{
                    $amount = (int)$incomes->amount;
                }
            }

            return response([
                "data" => [
                    'balance' => $balance,
                    'amount' => $amount
                ],
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $amount = 0;
            $balance = 0;

            $incomes = DB::table('incomes')
                ->select([
                    DB::raw("SUM(amount) as amount")
                ])
                ->where('coach_id', Auth::guard('coach')->user()->id)
                ->whereNull('deleted_at')
                ->first();

            if($incomes){
                $income_transactions = DB::table('income_transactions')
                    ->select([
                        DB::raw("SUM(total) as balance")
                    ])
                    ->where('coach_id', Auth::guard('coach')->user()->id)
                    ->whereNull('deleted_at')
                    ->first();

                if($income_transactions){
                    $amount = (int)$incomes->amount - (int)$income_transactions->balance;
                }else{
                    $amount = (int)$incomes->amount;
                }
            }

            if((int)$request->amount > $amount){
                return response([
                    "message"   => 'Saldo tidak cukup.'
                ], 400);
            }

            $result = DB::transaction(function () use($request){
                $tran_number = IncomeTransaction::orderBy(DB::raw("SUBSTRING(number, 9, 4)::INTEGER"),'desc')->withTrashed()->first();

                if($tran_number){
                    $str = explode("MREQ".date('Y'), $tran_number->number);
                    $number = sprintf("%04d", (int)$str[1] + 1);
                    $number = "MREQ".date('Y').$number;
                }else{
                    $number = "MREQ".date('Y').'0001';
                }

                $transaction = IncomeTransaction::create([
                    'number' => $number,
                    'coach_id' => Auth::guard('coach')->user()->id,
                    'status' => 1,
                    'total' => $request->amount,
                    'datetime' => date('Y-m-d H:i:s'),
                    'bank' => $request->bank,
                    'bank_number' => $request->bank_number,
                    'name_account' => $request->name_account
                ]);

                $amount = 0;
                $balance = 0;

                $incomes = DB::table('incomes')
                    ->select([
                        DB::raw("SUM(amount) as amount")
                    ])
                    ->where('coach_id', Auth::guard('coach')->user()->id)
                    ->whereNull('deleted_at')
                    ->first();

                if($incomes){
                    $income_transactions = DB::table('income_transactions')
                        ->select([
                            DB::raw("SUM(total) as balance")
                        ])
                        ->where('coach_id', Auth::guard('coach')->user()->id)
                        ->whereNull('deleted_at')
                        ->first();

                    if($income_transactions){
                        $amount = (int)$incomes->amount - (int)$income_transactions->balance;
                        $balance = (int)$income_transactions->balance;
                    }else{
                        $amount = (int)$incomes->amount;
                    }
                }

                return [
                    'balance' => $balance,
                    'amount' => $amount
                ];
            });

            return response([
                "data" => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }
}

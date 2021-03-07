<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\IncomeTransaction;

use DataTables;
use Storage;
use Auth;

class CoachController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Transaction'
            ],
            [
                'title' => 'Coach'
            ],
        ];

        return view('admin.transaction.coach.index', [
            'title' => 'Transaction Coach',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        try {
            $path = Storage::disk('s3')->url('/');

            $data = DB::table('income_transactions')
                ->select([
                    'income_transactions.id',
                    'income_transactions.number',
                    'income_transactions.bank',
                    'income_transactions.bank_number',
                    'income_transactions.name_account',
                    'income_transactions.status',
                    'income_transactions.total',
                    'income_transactions.datetime',
                    DB::raw("CASE
                        WHEN income_transactions.status = 1
                            THEN 'Waiting'
                        WHEN income_transactions.status = 2
                            THEN 'Success'
                        ELSE 'Cancel'
                    END status_text"),
                    'income_transactions.image',
                    DB::raw("CONCAT('{$path}',income_transactions.image) as image_url"),
                    'coaches.name as coach'
                ])
                ->leftJoin('coaches','coaches.id','=','income_transactions.coach_id')
                ->whereNull([
                    'income_transactions.deleted_at'
                ])
                ->get();

            return DataTables::of($data)->addIndexColumn()->make(true);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function confirm(Request $request, $id)
    {
        try{
            if(!isset($request->image)){
                return response([
                    "message"   => 'Gambar harus diisi!'
                ], 400);
            }

            $result = DB::transaction(function () use($request, $id){
                $file = $request->file('image');
                $path = Storage::disk('s3')->put('media', $file);

                $result = IncomeTransaction::find($id)->update([
                    'image' => $path,
                    'status' => 2,
                    'confirmed' => true,
                    'confirmed_at' => date('Y-m-d H:i:s')
                ]);

                return $result;
            });

            return response([
                "status"  => 200,
                "data"    => $result,
                "message" => 'OK!'
            ],200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }
}

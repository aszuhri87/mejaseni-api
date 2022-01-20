<?php

namespace App\Http\Controllers\Admin\Report\Transaction;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\CoachTransaction;

use DataTables;
use Storage;
use PDF;

class CoachController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Report'
            ],
            [
                'title' => 'Transaction'
            ],
            [
                'title' => 'Coach'
            ],
        ];

        return view('admin.report-transaction.coach.index', [
            'title' => 'Coach',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt(Request $request)
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
                ->where(function($query) use($request){
                    if(isset($request->date_from) && isset($request->date_to)){
                        $query->whereDate('income_transactions.datetime','>=', $request->date_from)
                            ->whereDate('income_transactions.datetime','<=', $request->date_to);
                    }

                    if(isset($request->status)){
                        $query->where('income_transactions.status', $request->status);
                    }
                })
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

    public function print_excel(Request $request)
    {
        return Excel::download(new CoachTransaction($request->date_from,$request->date_to, $request->status), 'coach-transaction-'.date('d-m-Y').'.xlsx');
    }

    public function print_pdf(Request $request)
    {
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
            ->where(function($query){
                if(isset($request->date_from) && isset($request->date_to)){
                    $query->whereDate('income_transactions.datetime','>=', $request->date_from)
                        ->whereDate('income_transactions.datetime','<=', $request->date_to);
                }

                if(isset($request->status)){
                    $query->where('income_transactions.status', $request->status);
                }
            })
            ->whereNull([
                'income_transactions.deleted_at'
            ])
            ->get();


        $pdf = PDF::loadview('admin.print.pdf.transaction-coach',compact('data'))->setPaper('a4', 'landscape');
        return $pdf->download('transaction-coach-'.date('d-m-Y').'.pdf');
    }
}

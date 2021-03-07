<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Storage;

class CoachTransaction implements FromView, ShouldAutoSize
{
    protected $date_from;
    protected $date_to;
    protected $status;

    public function __construct($date_from,$date_to, $status) {
            $this->date_from = $date_from;
            $this->date_to = $date_to;
            $this->status = $status;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
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
                if(isset($this->date_from) && isset($this->date_to)){
                    $query->whereDate('income_transactions.datetime','>=', $this->date_from)
                        ->whereDate('income_transactions.datetime','<=', $this->date_to);
                }

                if(isset($this->status)){
                    $query->where('income_transactions.status', $this->status);
                }
            })
            ->whereNull([
                'income_transactions.deleted_at'
            ])
            ->get();

        return view('admin.print.excel.transaction-coach', [
            'data' => $data
        ]);
    }
}

<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

use App\Models\IncomeSetting;

class IncomeExport extends DefaultValueBinder implements FromView, WithCustomValueBinder, ShouldAutoSize
{
    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $day = 'friday';
        $time = '17:00:00';

        $setting = IncomeSetting::first();

        if($setting){
            $day = $setting->day;
            $time = $setting->last_request;
        }

        $previous_week = date('Y-m-d', strtotime("{$day} previous week"))." {$time}";
        $this_week = date('Y-m-d', strtotime("{$day} this week"))." {$time}";
        $next_week = date('Y-m-d', strtotime("{$day} next week"))." {$time}";

        $data = DB::table('income_transactions')
            ->select([
                'income_transactions.id',
                'income_transactions.number',
                'income_transactions.datetime',
                'income_transactions.bank',
                'income_transactions.bank_number',
                'income_transactions.name_account',
                'income_transactions.total',
                'coaches.name'
            ])
            ->leftJoin('coaches','coaches.id','=','income_transactions.coach_id')
            ->whereRaw("income_transactions.datetime::timestamp >= '{$previous_week}'::timestamp")
            ->whereRaw("income_transactions.datetime::timestamp <= '{$this_week}'::timestamp")
            ->where('income_transactions.confirmed', false)
            ->where('income_transactions.approved', true)
            ->get();

        return view('exports.excel.income', [
            'transactions' => $data,
            'session_date' => date('Y-m-d', strtotime("{$day} this week"))
        ]);
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }
}

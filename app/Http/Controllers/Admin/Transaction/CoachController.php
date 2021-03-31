<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\IncomeTransaction;
use App\Models\IncomeSetting;

use App\Exports\IncomeExport;

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

    public function dt(Request $request)
    {
        try {
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

            $path = Storage::disk('s3')->url('/');

            $status = DB::table('income_transactions')
                ->select([
                    'income_transactions.id',
                    DB::raw("'{$previous_week}' as previous_week"),
                    DB::raw("'{$this_week}' as this_week"),
                    DB::raw("'{$next_week}' as next_week"),
                    DB::raw("CASE
                        WHEN income_transactions.datetime::timestamp >= '{$previous_week}'::timestamp
                            AND income_transactions.datetime::timestamp <= '{$this_week}'::timestamp
                            AND income_transactions.approved = false
                            THEN 1
                        WHEN income_transactions.datetime::timestamp >= '{$previous_week}'::timestamp
                            AND income_transactions.datetime::timestamp <= '{$this_week}'::timestamp
                            AND income_transactions.approved = true
                            THEN 2
                        WHEN income_transactions.status = 1
                            AND income_transactions.confirmed = false
                            THEN 3
                        WHEN income_transactions.status = 2
                            AND income_transactions.confirmed = true
                            THEN 4
                        ELSE 5
                    END status"),
                    DB::raw("CASE
                        WHEN income_transactions.datetime::timestamp >= '{$previous_week}'::timestamp
                            AND income_transactions.datetime::timestamp <= '{$this_week}'::timestamp
                            AND income_transactions.approved = false
                            THEN 'Waiting Approve'
                        WHEN income_transactions.datetime::timestamp >= '{$previous_week}'::timestamp
                            AND income_transactions.datetime::timestamp <= '{$this_week}'::timestamp
                            AND income_transactions.approved = true
                            THEN 'Waiting Transfer'
                        WHEN income_transactions.status = 1
                            AND income_transactions.confirmed = false
                            THEN 'Waiting Session'
                        WHEN income_transactions.status = 2
                            AND income_transactions.confirmed = true
                            THEN 'Success'
                        ELSE 'Cancel'
                    END status_text"),
                    DB::raw("CASE
                        WHEN income_transactions.datetime::timestamp >= '{$previous_week}'::timestamp
                            AND income_transactions.datetime::timestamp <= '{$this_week}'::timestamp
                            AND income_transactions.approved = false
                            THEN 'danger'
                        WHEN income_transactions.datetime::timestamp >= '{$previous_week}'::timestamp
                            AND income_transactions.datetime::timestamp <= '{$this_week}'::timestamp
                            AND income_transactions.approved = true
                            THEN 'warning'
                        WHEN income_transactions.status = 1
                            AND income_transactions.confirmed = false
                            THEN 'primary'
                        WHEN income_transactions.status = 2
                            AND income_transactions.confirmed = true
                            THEN 'success'
                        ELSE 'secondary'
                    END status_color"),
                ]);

            $data = DB::table('income_transactions')
                ->select([
                    'income_transactions.id',
                    'income_transactions.number',
                    'income_transactions.bank',
                    'income_transactions.bank_number',
                    'income_transactions.name_account',
                    'income_transactions.total',
                    'income_transactions.datetime',
                    'data_status.previous_week',
                    'data_status.this_week',
                    'data_status.next_week',
                    'data_status.status_text',
                    'data_status.status_color',
                    'data_status.status',
                    'income_transactions.image',
                    DB::raw("CONCAT('{$path}',income_transactions.image) as image_url"),
                    'coaches.name as coach'
                ])
                ->leftJoin('coaches','coaches.id','=','income_transactions.coach_id')
                ->leftJoinSub($status, 'data_status', function($join){
                    $join->on('data_status.id', 'income_transactions.id');
                })
                ->where(function($query) use($request){
                    if(isset($request->date_from) && isset($request->date_to)){
                        $query->whereDate('income_transactions.datetime','>=', $request->date_from)
                            ->whereDate('income_transactions.datetime','<=', $request->date_to);
                    }

                    if(isset($request->status)){
                        $query->where('data_status.status', $request->status);
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

    public function approve($id)
    {
        try{
            $result = DB::transaction(function () use($id){
                $result = IncomeTransaction::find($id)->update([
                    'approved' => true,
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

    public function export(Request $request)
    {
        $name = 'Transfer-Approval-'.date('Y-m-d');
        return \Excel::download(new IncomeExport($request), $name.'.xlsx');
    }
}

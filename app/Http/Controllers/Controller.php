<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function restoration($model, $param = [])
    {
        try {
            $data = $model::where($param)
                ->withTrashed()
                ->first();

            if($data->deleted_at){
                \Illuminate\Support\Facades\DB::transaction(function () use($data){
                    $this->func_loop($data);
                });

                return true;
            }else{
                return 'deleted_at is null';
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function func_loop($data)
    {
        $deletedAt = \Carbon\Carbon::parse($data->deleted_at)->format('Y-m-d H:i:s');
        $endDeletedAt = \Carbon\Carbon::parse($deletedAt)->addSeconds(10)->format('Y-m-d H:i:s');
        if(!empty($data->cascadeDeletes)){
            foreach ($data->cascadeDeletes as $self_func) {
                $children = $data->{$self_func}()
                    ->whereRaw("deleted_at::timestamp >= '$deletedAt'::timestamp")
                    ->whereRaw("deleted_at::timestamp <= '$endDeletedAt'::timestamp")
                    ->withTrashed()
                    ->get();

                if(!empty($children)){
                    foreach ($children as $children_data) {
                        $this->func_loop($children_data);
                    }

                    $data->{$self_func}()
                        ->whereRaw("deleted_at::timestamp >= '$deletedAt'::timestamp")
                        ->whereRaw("deleted_at::timestamp <= '$endDeletedAt'::timestamp")
                        ->withTrashed()
                        ->update([
                            'deleted_at' => null
                        ]);
                }
            }
        }

        $data->deleted_at = null;
        $data->update();

        return true;
    }
}

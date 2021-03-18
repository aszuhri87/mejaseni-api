<?php

namespace App\Traits;

trait RestoreSoftDeletes
{
    protected function restore_soft_deletes($model)
    {
        try {
            if($model->deleted_at){
                $this->restore_func_loop($model);

                return true;
            }else{
                return 'deleted_at is null';
            }
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    protected function restore_func_loop($data)
    {
        $deletedAt = \Carbon\Carbon::parse($data->deleted_at)->format('Y-m-d H:i:s');

        if(!empty($data->cascadeDeletes)){
            foreach ($data->cascadeDeletes as $self_func) {
                $children = $data->{$self_func}()
                    ->whereRaw("deleted_at::timestamp >= '$deletedAt'::timestamp - INTERVAL '5 SECONDS'")
                    ->whereRaw("deleted_at::timestamp <= '$deletedAt'::timestamp + INTERVAL '5 SECONDS'")
                    ->withTrashed()
                    ->get();

                if(!empty($children)){
                    foreach ($children as $children_data) {
                        $this->restore_func_loop($children_data);
                    }

                    $data->{$self_func}()
                        ->whereRaw("deleted_at::timestamp >= '$deletedAt'::timestamp - INTERVAL '5 SECONDS'")
                        ->whereRaw("deleted_at::timestamp <= '$deletedAt'::timestamp  + INTERVAL '5 SECONDS'")
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

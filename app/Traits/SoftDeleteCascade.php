<?php

namespace App\Traits;

trait SoftDeleteCascade
{
    protected static function bootSoftDeleteCascade()
    {
        static::deleting(function ($model) {
            try {
                $deletedAt = date('Y-m-d H:i:s');
                $model->delete_func_loop($model, $deletedAt);
            } catch (\Exception $e) {
                abort(500, $e->getMessage());
            }
        });
    }

    protected function delete_func_loop($data, $deletedAt)
    {
        if(!empty($data->cascadeDeletes)){
            foreach ($data->cascadeDeletes as $self_func) {
                $children = $data->{$self_func}()->get();

                if(!empty($children)){
                    foreach ($children as $children_data) {
                        $this->delete_func_loop($children_data, $deletedAt);
                    }

                    $data->{$self_func}()
                        ->update([
                            'deleted_at' => $deletedAt
                        ]);
                }
            }
        }

        $data->deleted_at = $deletedAt;
        $data->update();

        return true;
    }
}

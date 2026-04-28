<?php

namespace App\Traits;

use App\Facades\Audit;

trait Auditable
{
    /**
     * The "booting" method of the trait.
     */
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            $data = $model->getAttributes();

            // Add a friendly name for the log if possible
            if (isset($model->nama_lengkap)) {
                $data['__display_name'] = $model->nama_lengkap;
            }

            Audit::log(
                'CREATE_'.strtoupper(class_basename($model)),
                $model,
                $data
            );
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            $oldValues = array_intersect_key($model->getRawOriginal(), $changes);

            if (isset($model->nama_lengkap)) {
                $changes['__display_name'] = $model->nama_lengkap;
            }

            Audit::log(
                'UPDATE_'.strtoupper(class_basename($model)),
                $model,
                $changes,
                $oldValues
            );
        });

        static::deleted(function ($model) {
            $data = [];
            if (isset($model->nama_lengkap)) {
                $data['__display_name'] = $model->nama_lengkap;
            }

            Audit::log(
                'DELETE_'.strtoupper(class_basename($model)),
                $model,
                $data,
                $model->getRawOriginal()
            );
        });
    }
}

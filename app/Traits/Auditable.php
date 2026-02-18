<?php
namespace App\Traits;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            audit_log(
                'created',
                'تم إنشاء سجل جديد',
                class_basename($model),
                $model->id
            );
        });

        static::updated(function ($model) {
            audit_log(
                'updated',
                'تم تعديل السجل',
                class_basename($model),
                $model->id
            );
        });

        static::deleted(function ($model) {
            audit_log(
                'deleted',
                'تم حذف السجل',
                class_basename($model),
                $model->id
            );
        });
    }
}

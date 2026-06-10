<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            $slugSource = $model->getSlugSourceColumn();
            if (empty($model->slug) && ! empty($model->$slugSource)) {
                $model->slug = static::generateUniqueSlug($model->$slugSource);
            }
        });

        static::updating(function ($model) {
            $slugSource = $model->getSlugSourceColumn();
            if ($model->isDirty($slugSource)) {
                $model->slug = static::generateUniqueSlug($model->$slugSource, $model->id);
            }
        });
    }

    protected function getSlugSourceColumn(): string
    {
        return 'title'; // default fallback
    }

    protected static function generateUniqueSlug(string $value, ?int $excludeId = null): string
    {
        $slug = Str::slug($value);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)
            ->when($excludeId, function ($query) use ($excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->exists()
        ) {
            $slug = $originalSlug.'-'.$count++;
        }

        return $slug;
    }
}

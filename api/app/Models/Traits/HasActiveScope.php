<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasActiveScope
{
    public function scopeActive(Builder $query): Builder
    {
        $query->where('is_active', true);

        foreach ($this->activeRelations() as $relation) {
            $query->whereHas($relation, fn (Builder $q) => $q->active());
        }

        return $query;
    }

    protected function activeRelations(): array
    {
        return [];
    }
}

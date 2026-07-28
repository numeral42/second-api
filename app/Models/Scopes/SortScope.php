<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SortScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        //aplicar ordenamiento
        if (empty(request('sort'))) {
            return;
        }
        $sort = request('sort');
        $sortArray = explode(',', $sort);
        foreach ($sortArray as $sortField) {
            $direction = 'asc';
            if (substr($sortField, 0, 1) === '-') {
                $direction = 'desc';
                $sortField = substr($sortField, 1);
            }
            $builder->orderBy($sortField, $direction);
        }
    }
}

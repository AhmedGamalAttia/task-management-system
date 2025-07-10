<?php

namespace App\Filters;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class TaskFilters
{
    public static function apply($model)
    {
        return QueryBuilder::for($model)
            ->allowedFilters([
                'title',
                'description',
                'status',
                'user_id',

                AllowedFilter::callback('due_date_from', function (Builder $query, $value) {
                    $query->where('due_date', '>=', Carbon::parse($value)->format('Y-m-d'));
                }),

                AllowedFilter::callback('due_date_to', function (Builder $query, $value) {
                    $query->where('due_date', '<=', Carbon::parse($value)->format('Y-m-d'));
                }),
            ]);
    }
}

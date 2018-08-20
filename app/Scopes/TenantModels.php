<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;

trait TenantModels
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new TenantScope());

        static::creating(function (Model $model) {
            if (!\Auth::guest() && false !== array_search('company_id', $model->getFillable())) {
                $model->company_id = \Auth::user()->company_id;
            }
        });

    }
}
<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait DealsTenantable
{

    protected static function bootDealsTenantable()
    {
        if (auth()->check()) {

            static::creating(function ($model) {
                $model->created_by = auth()->id();
                $model->department_id = auth()->user()->department_id;
            });

            static::updating(function ($model) {
                $model->updated_by = auth()->id();
            });

            $l[] = json_encode(auth()->id());


            if (auth()->user()->hasPermissionTo('simple-user')) {
                static::addGlobalScope('user_id', function (Builder $builder) use ($l) {
                    $builder->whereJsonContains('sellers', $l)
                        ->orWhere('user_id', auth()->id());
                });
            }

            if (auth()->user()->hasPermissionTo('team-manager')) {
                static::addGlobalScope('team_id', function (Builder $builder) {
                    $builder->whereIn('team_id', auth()->user()->ownedTeams->pluck('id'))
                        ->where('department_id', auth()->user()->department_id);;
                });
            }
        }
    }

}

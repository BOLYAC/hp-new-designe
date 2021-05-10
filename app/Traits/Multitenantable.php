<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Multitenantable
{
    protected static function bootMultitenantable()
    {
        if (auth()->check()) {

            static::creating(function ($model) {
                $model->created_by = auth()->id();
            });

            static::updating(function ($model) {
                $model->updated_by = auth()->id();
            });

            if (auth()->user()->hasPermissionTo('simple-user')) {
                static::addGlobalScope('user_id', function (Builder $builder) {
                    $builder->where('user_id', auth()->id());
                });
            }
            
            if (auth()->user()->hasPermissionTo('team-manager')) {
              static::addGlobalScope('team_id', function (Builder $builder) {
                  $builder->where('team_id', auth()->user()->currentTeam->id);
              });
            }
        }
    }
}

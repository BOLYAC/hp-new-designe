<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait DealsTenantable
{

    protected static function bootDealsTenantable()
    {
        if (auth()->check()) {

            //$roleName = auth()->user()->getRoleNames();

            $l[] = json_encode(auth()->id());


            if (auth()->user()->hasPermissionTo('simple-user')) {
                static::addGlobalScope('user_id', function (Builder $builder) use ($l) {
                  $builder->whereJsonContains('sellers', $l)
                    ->orWhere('user_id', auth()->id());
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

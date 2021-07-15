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
                    $builder->orWhere('user_id', auth()->id())
                        ->whereJsonContains('sellers', $l);
                });
            }

            if (auth()->user()->hasPermissionTo('team-manager')) {
                if (auth()->user()->ownedTeams()->count() > 0) {
                    $teamUsers = auth()->user()->ownedTeams;
                    $teams = auth()->user()->allTeams();
                    foreach ($teamUsers as $u) {
                        foreach ($u->users as $ut) {
                            $users[] = $ut->id;
                        }
                    }
                    static::addGlobalScope('team_id', function (Builder $builder) use ($users) {
                        $builder->whereIn('user_id', $users);
                    });
                }
            }

            /*if (auth()->user()->hasPermissionTo('desk-manager')) {
                if (auth()->user()->ownedTeams()->count() > 0) {
                    $teamUsers = auth()->user()->ownedTeams;
                    $teams = auth()->user()->allTeams();
                    foreach ($teams as $u) {
                        foreach ($u->users as $ut) {
                            $users[] = $ut;
                        }
                    }
                    static::addGlobalScope('team_id', function (Builder $builder) use ($users) {
                        $builder->whereIn('user_id', $users);
                    });
                }
            }*/

        }
    }
}

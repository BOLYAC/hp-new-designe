<?php

namespace App\Models;

use App\Traits\DealsTenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Event extends Model implements Auditable

{
  use \OwenIt\Auditing\Auditable;
  use SoftDeletes, DealsTenantable;

  protected $guarded = [];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'event_date' => 'datetime:Y-m-d',
    'lang' => 'array',
    'sellers' => 'array',
    'sells_name' => 'array',
    'budget' => 'array',
    'lead_lang' => 'array'
  ];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function client()
  {
    return $this->belongsTo(Client::class, 'client_id')->withDefault();
  }

  public function lead()
  {
    return $this->belongsTo(Lead::class, 'lead_id')->withDefault();
  }

  public function SharedEvents(): BelongsToMany
  {
    return $this->belongsToMany(User::class, EventUser::class)
      ->withPivot('user_name', 'added_by')
      ->withTimestamps()
      ->as('sharedEvents');
  }

  public static function boot()
  {
    parent::boot();

    static::creating(function ($event) { // On create() method call this
      $event->team_id = auth()->user()->currentTeam->id ?? '1';
    });
  }
}

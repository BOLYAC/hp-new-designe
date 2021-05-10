<?php

namespace App\Models;

use App\Agency;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Client extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  use SoftDeletes, Multitenantable;

  /**
   * @var array
   */
  protected $guarded = [];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'appointment_date' => 'datetime',
    'next_call' => 'datetime',
    'type' => 'boolean',
    'spoken' => 'boolean',
    'called' => 'boolean',
    'lang' => 'array',
    'country' => 'array',
    'nationality' => 'array',
      'budget_request' => 'array',
      'rooms_request' => 'array',
      'requirements_request' => 'array'
  ];

  /**
   * @return BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  /**
   * @return BelongsTo
   */
  public function agency(): BelongsTo
  {
    return $this->belongsTo(Agency::class, 'agency_id', 'id');
  }

  /**
   * @return BelongsTo
   */
  public function updateBy(): BelongsTo
  {
    return $this->belongsTo(User::class, 'updated_by', 'id');
  }

  /**
   * @return BelongsTo
   */
  public function source(): BelongsTo
  {
    return $this->belongsTo(Source::class, 'source_id', 'id')->withDefault();
  }

  /**
   * @return HasMany
   */
  public function notes(): HasMany
  {
    return $this->hasMany(Note::class)->orderByDesc('created_at');
  }

  /**
   * @return HasMany
   */
  public function tasks(): HasMany
  {
    return $this->hasMany(Task::class);
  }

  /**
   * @return HasMany
   */
  public function events(): HasMany
  {
    return $this->hasMany(Event::class);
  }

  /**
   * @return HasMany
   */
  public function leads(): HasMany
  {
    return $this->hasMany(Lead::class, 'client_id', 'id')
        ->orderBy('created_at', 'desc');
  }


  /**
   * @return HasMany
   */
  public function invoices(): HasMany
  {
    return $this->hasMany(Invoice::class);
  }

  /**
   * @return HasMany
   */
  public function documents(): HasMany
  {
    return $this->hasMany(ClientDocument::class);
  }

  // this is a recommended way to declare event handlers

  public static function boot()
  {
    parent::boot();

    static::deleting(function ($client) { // before delete() method call this
      $client->tasks()->delete();
      // do the rest of the cleanup...
    });

    static::creating(function ($client) { // On create() method call this
      $client->public_id = strtoupper(substr(uniqid(mt_rand(), true), 16, 6));
      $client->team_id = auth()->user()->currentTeam->id ?? '1';
    });
  }
}

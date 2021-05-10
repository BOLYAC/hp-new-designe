<?php

namespace App;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agency extends Model
{
  use SoftDeletes;

  protected $guarded = [];

  protected $fillable = [
    "title",
    "name" ,
    "in_charge",
    "tax_number",
    "phone" ,
    "email" ,
    "commission_rate" ,
    "contract_status" ,
    "address" ,
    "note" ,
    "status"
  ];

   /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'status' => 'boolean'
  ];

  public function clients(): HasMany
  {
      return $this->hasMany(Client::class);
  }

  public static function boot()
  {
    parent::boot();

    static::creating(function ($agency) { // On create() method call this
      $agency->team_id = auth()->user()->currentTeam->id ?? '1';
      $agency->user_id = auth()->id();
      $agency->created_by = auth()->id();
      $agency->updated_by = auth()->id();
    });

    static::updating(function ($agency) { // On create() method call this
      $agency->updated_by = auth()->id();
    });
  }

}

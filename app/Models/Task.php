<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Task extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  use SoftDeletes, Multitenantable;


   /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['client'];

  protected $fillable = [
    'title', 'date', 'user_id', 'client_id', 'archive', 'team_id'
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'date' => 'datetime',
    'archive' => 'boolean'
  ];

  public $tableName = "Task";
  public function getTableName()
  {
    return $this->tableName;
  }

  public function scopeArchive($query, $value)
  {
    return $query->where('archive', $value);
  }


  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function client()
  {
    return $this->belongsTo(Client::class, 'client_id')->withDefault();
  }


  public static function boot()
  {
    parent::boot();

    static::creating(function ($task) { // On create() method call this
      $task->team_id = auth()->user()->currentTeam->id ?? '1';
    });
  }
}

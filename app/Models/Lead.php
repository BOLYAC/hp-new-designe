<?php

namespace App\Models;

use App\Services\Comment\Commentable;
use App\Traits\DealsTenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Lead extends Model implements Commentable
{
    use SoftDeletes, DealsTenantable;

    protected $guarded = [];

    protected $dates = ['deadline'];

    protected $casts = [
        'sellers' => 'array',
        'sells_names' => 'array'
    ];

    public function displayValue()
    {
        return $this->title;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function stageLog()
    {
        return $this->hasMany(StageLog::class, 'lead_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function events(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'source');
    }

    public function getCreateCommentEndpoint(): string
    {
        return route('comments.create', ['type' => 'lead', 'external_id' => $this->external_id]);
    }


    public function getAssignedUserAttribute()
    {
        return User::findOrFail($this->user_assigned_id);
    }


    public function ShareWithSelles(): BelongsToMany
    {
        return $this->belongsToMany(User::class, SharedLead::class)
            ->withPivot('user_name', 'added_by')
            ->withTimestamps()
            ->as('sharedLeads');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function convertToOrder()
    {
        if (!$this->canConvertToOrder()) {
            return false;
        }
        $invoice = Invoice::create([
            'status' => 'draft',
            'client_id' => $this->client->id,
            'external_id' => Uuid::uuid4()->toString()
        ]);
        dd($invoice);
        $this->invoice_id = $invoice->id;
        //$this->status_id = Status::typeOfLead()->where('title', 'Closed')->first()->id;
        $this->save();

        return $invoice;
    }

    public function canConvertToOrder()
    {
        if ($this->invoice) {
            return false;
        }
        return true;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($lead) { // On create() method call this
            $lead->team_id = auth()->user()->currentTeam->id ?? '1';
        });
    }
}

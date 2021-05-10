<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientDocument extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'client_documents';

    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [
        'client_id' => 'integer',
    ];


    public function client()
    {
        return $this->belongsTo(Client::class)->withDefault();
    }

    public function documents()
    {
        return $this->belongsTo(Invoice::class)->withDefault();
    }
}

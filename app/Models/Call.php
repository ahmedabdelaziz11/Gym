<?php

namespace App\Models;

use App\Traits\ShowableTrait;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    use ShowableTrait;
    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class,'client_id');
    }
}

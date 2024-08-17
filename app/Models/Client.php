<?php

namespace App\Models;

use App\Traits\ShowableTrait;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use ShowableTrait;
    protected $guarded = [];

    public function seller()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}

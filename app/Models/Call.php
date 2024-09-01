<?php

namespace App\Models;

use App\Traits\BranchTrait;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    use BranchTrait;
    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class,'client_id');
    }
}

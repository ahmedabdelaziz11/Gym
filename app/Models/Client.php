<?php

namespace App\Models;

use App\Traits\BranchTrait;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use BranchTrait;
    protected $guarded = [];

    public function seller()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}

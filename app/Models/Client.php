<?php

namespace App\Models;

use App\Constants\ClientStatus;
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

    public function scopeLead($q)
    {
        return $q->where('client_status', '!=', ClientStatus::CONVERTED);
    }

    public function scopeClient($q)
    {
        return $q->where('client_status', ClientStatus::CONVERTED);
    }
}

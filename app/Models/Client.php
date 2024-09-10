<?php

namespace App\Models;

use App\Enums\ClientType;
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
    
    public function isLead() :bool
    {
        return $this->client_type == ClientType::LEAD->value;
    }
    
    public function scopeLead($q)
    {
        return $q->where('client_type',ClientType::LEAD->value);
    }

    public function scopeClient($q)
    {
        return $q->where('client_type', ClientType::SUBSCRIBER->value);
    }

    public function subscription()
    {
        return $this->hasMany(Subscription::class);
    }

    public function latestSubscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }
}

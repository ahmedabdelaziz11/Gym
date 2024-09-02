<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'client_id',
        'plan_id',
        'start_date',
        'end_date',
        'status',
        'price',
        'sessions_left',
        'branch_id'
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'subscription_service')->withPivot('quantity');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}

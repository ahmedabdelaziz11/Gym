<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionService extends Model
{
    protected $table = 'subscription_service';

    protected $fillable = [
        'subscription_id',
        'service_id',
        'quantity',
    ];
}

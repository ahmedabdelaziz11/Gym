<?php

namespace App\Models;

use App\Traits\ShowableTrait;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use ShowableTrait;
    protected $guarded = [];

    public function services()
    {
        return $this->belongsToMany(Service::class)->withPivot('count')->withTimestamps();
    }
}

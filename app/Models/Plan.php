<?php

namespace App\Models;

use App\Traits\BranchTrait;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use BranchTrait;
    protected $guarded = [];

    public function services()
    {
        return $this->belongsToMany(Service::class)->withPivot('count')->withTimestamps();
    }
}

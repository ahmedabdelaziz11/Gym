<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchShowable extends Model
{
    protected $guarded = [];

    public function showable()
    {
        return $this->morphTo();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}

<?php 

namespace App\Traits;

use App\Models\Branch;

trait BranchTrait
{
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function scopeForBranch($query, $branch_id)
    {
        return $query->where('branch_id', $branch_id);
    }
}

<?php 

namespace App\Traits;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Builder;

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

    protected static function booted()
    {
        static::addGlobalScope('yourScopeName', function (Builder $builder) {
            $builder->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray());
        });
    }
}

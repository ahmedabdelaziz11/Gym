<?php 

namespace App\Traits;

use App\Models\BranchShowable;

trait ShowableTrait
{
    public function showable()
    {
        return $this->morphOne(BranchShowable::class, 'showable');
    }

    public function createOrUpdateShowables($branch_id){
        $branch = $this->showable()->firstOrNew([
            'showable_id' => $this->id,
            'showable_type' => get_class($this),
        ]);
        $branch->branch_id = $branch_id;
        return $branch->save();
    }

    public function scopeForbranch($query, $branch_id)
    {
        return $query->whereHas('showable', function ($q) use ($branch_id) {
            $q->where('branch_id', $branch_id);
        });
    }


    public static function bootShowableTrait()
    {
        static::deleted(function ($model) {
            $model->deleteShowables();
        });
    }

    public function deleteShowables()
    {
        $this->showable()->delete();
    }
}

<?php

namespace App\Models;

use App\Traits\BranchTrait;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use BranchTrait;
    protected $guarded = [];

}

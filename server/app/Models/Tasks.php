<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasks extends Model
{
    use SoftDeletes;

    public const STATUS_PENDING   = 0;
    public const STATUS_COMPLETED = 1;

    protected $fillable = [
        'title',
        'filename',
        'filepath',
        'status'
    ];
}

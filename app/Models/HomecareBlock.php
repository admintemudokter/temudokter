<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomecareBlock extends Model
{
    protected $fillable = [
        'type',
        'date',
        'time',
        'reason',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelVariabel extends Model
{
    use HasFactory;

    // protected $table = 'var_awal';

    protected $fillable = [
        'bdv',
        'water',
        'acid',
        'ift',
        'color'
    ];
}

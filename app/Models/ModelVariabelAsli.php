<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelVariabelAsli extends Model
{
    use HasFactory;

    protected $fillable = [
        'bdv',
        'water',
        'acid',
        'ift',
        'color'
    ];
}

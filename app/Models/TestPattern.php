<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPattern extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'biology_percentage',
        'chemistry_percentage',
        'physics_percentage',
        'english_percentage',
        'reasoning_percentage',
        'status',
    ];
}
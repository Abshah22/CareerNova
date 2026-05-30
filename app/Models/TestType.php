<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mcq_count',
        'duration_minutes',
        'price',
        'description',
        'tier',
        'status',
    ];
}
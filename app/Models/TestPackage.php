<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'total_questions',
        'time_limit_minutes',
        'passing_percentage',
        'configuration',
    ];

    protected $casts = [
        'configuration' => 'array',
    ];

    public function getQuestionsAttribute()
    {
        $config = $this->configuration ?? [];
        $questions = [];

        foreach ($config as $subject => $data) {
            $percentage = $data['percentage'] ?? 0;
            $count = (int)(($percentage / 100) * $this->total_questions);

            $mcqs = Mcq::where('subject_id', $data['subject_id'] ?? 0)
                ->where('status', 'active')
                ->inRandomOrder();

            // Get balanced difficulty
            $easy = (int)($count * 0.33);
            $medium = (int)($count * 0.33);
            $hard = $count - $easy - $medium;

            $questions[$subject] = [
                'easy' => $mcqs->clone()->where('difficulty', 'easy')->limit($easy)->get(),
                'medium' => $mcqs->clone()->where('difficulty', 'medium')->limit($medium)->get(),
                'hard' => $mcqs->clone()->where('difficulty', 'hard')->limit($hard)->get(),
            ];
        }

        return collect($questions)->flatten(1);
    }
}
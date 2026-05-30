<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnswerLog extends Model
{
    protected $fillable = [
        'exam_session_id',
        'mcq_id',
        'selected_answer',
        'correct_answer',
        'is_correct',
        'time_taken',
        'order',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: Belongs to ExamSession
     */
    public function examSession(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class);
    }

    /**
     * Relationship: Belongs to MCQ
     */
    public function mcq(): BelongsTo
    {
        return $this->belongsTo(Mcq::class);
    }
}

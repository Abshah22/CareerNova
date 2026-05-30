<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSession extends Model
{
    protected $fillable = [
        'user_id',
        'subject_id',
        'total_questions',
        'duration_minutes',
        'mcq_sequence',
        'started_at',
        'expires_at',
        'finished_at',
        'is_locked',
        'status',
        'score',
        'correct_answers',
        'wrong_answers',
        'unanswered',
        'percentage',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'finished_at' => 'datetime',
        'is_locked' => 'boolean',
        'percentage' => 'float',
    ];

    /**
     * Relationship: Belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Belongs to Subject
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relationship: Has Many AnswerLogs
     */
    public function answerLogs(): HasMany
    {
        return $this->hasMany(AnswerLog::class);
    }

    /**
     * Check if session is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expires_at && now()->lessThan($this->expires_at);
    }

    /**
     * Check if session is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && now()->greaterThanOrEqualTo($this->expires_at);
    }

    /**
     * Get time remaining in seconds
     */
    public function getTimeRemainingInSeconds(): int
    {
        if (!$this->expires_at || $this->status === 'completed') {
            return 0;
        }

        return max(0, $this->expires_at->diffInSeconds(now(), false));
    }
}

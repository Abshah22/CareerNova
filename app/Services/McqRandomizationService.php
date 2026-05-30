<?php

namespace App\Services;

use App\Models\Mcq;
use App\Models\ExamSession;
use Illuminate\Database\Eloquent\Collection;

class McqRandomizationService
{
    /**
     * Generate exam MCQs with dynamic subject distribution
     * MOST IMPORTANT: MCQs generated ONCE and stored permanently
     */
    public function generateExamMcqs($testType, $testPattern)
    {
        // 1. Calculate subject allocations dynamically
        $biologyCount = round($testType->mcq_count * ($testPattern->biology_percentage / 100));
        $chemistryCount = round($testType->mcq_count * ($testPattern->chemistry_percentage / 100));
        $physicsCount = round($testType->mcq_count * ($testPattern->physics_percentage / 100));
        $englishCount = round($testType->mcq_count * ($testPattern->english_percentage / 100));
        $reasoningCount = $testType->mcq_count - ($biologyCount + $chemistryCount + $physicsCount + $englishCount);

        $mcqs = collect();

        // 2. Select MCQs by subject with difficulty distribution
        $mcqs = $mcqs->merge($this->getMcqsBySubject('Biology', $biologyCount));
        $mcqs = $mcqs->merge($this->getMcqsBySubject('Chemistry', $chemistryCount));
        $mcqs = $mcqs->merge($this->getMcqsBySubject('Physics', $physicsCount));
        $mcqs = $mcqs->merge($this->getMcqsBySubject('English', $englishCount));
        $mcqs = $mcqs->merge($this->getMcqsBySubject('Logical Reasoning', $reasoningCount));

        // 3. Generate random order ONCE
        $randomized = $mcqs->shuffle();

        // 4. Return as array: [55, 91, 11, ...]
        return $randomized->pluck('id')->toArray();
    }

    /**
     * Get MCQs by subject with difficulty distribution
     */
    private function getMcqsBySubject($subjectName, $count)
    {
        if ($count <= 0) return collect();

        $difficultyDistribution = [
            'easy' => ceil($count * 0.33),
            'medium' => ceil($count * 0.33),
            'hard' => $count - (ceil($count * 0.33) * 2),
        ];

        $mcqs = collect();

        foreach ($difficultyDistribution as $difficulty => $qty) {
            $questions = Mcq::whereHas('subject', function ($q) use ($subjectName) {
                $q->where('name', $subjectName);
            })
                ->where('difficulty', $difficulty)
                ->where('status', 'active')
                ->inRandomOrder()
                ->limit($qty)
                ->get();

            $mcqs = $mcqs->merge($questions);
        }

        return $mcqs;
    }

    /**
     * Get randomized MCQs for an exam session
     */
    public function getRandomMcqs($subjectId, $count = 10)
    {
        return Mcq::where('subject_id', $subjectId)
            ->where('status', 'active')
            ->inRandomOrder()
            ->limit($count)
            ->get();
    }

    /**
     * Get randomized MCQs by difficulty level
     */
    public function getRandomMcqsByDifficulty($subjectId, $count = 10, $difficultyDistribution = [])
    {
        if (empty($difficultyDistribution)) {
            $difficultyDistribution = [
                'easy' => 0.3,
                'medium' => 0.5,
                'hard' => 0.2,
            ];
        }

        $mcqs = collect();

        foreach ($difficultyDistribution as $difficulty => $percentage) {
            $questionCount = ceil($count * $percentage);

            $questions = Mcq::where('subject_id', $subjectId)
                ->where('difficulty', $difficulty)
                ->where('status', 'active')
                ->inRandomOrder()
                ->limit($questionCount)
                ->get();

            $mcqs = $mcqs->merge($questions);
        }

        return $mcqs->shuffle()->take($count);
    }

    /**
     * Randomize option order for a question
     */
    public function randomizeOptions($mcq)
    {
        $options = [
            'A' => $mcq->option_a,
            'B' => $mcq->option_b,
            'C' => $mcq->option_c,
            'D' => $mcq->option_d,
        ];

        $shuffled = collect($options)->shuffle();

        $newCorrectAnswer = null;
        $newOptions = [];

        foreach ($shuffled as $newLetter => $optionText) {
            $newOptions[$newLetter] = $optionText;

            // Map original correct answer to new position
            if ($optionText === $mcq->getOptionByLetter($mcq->correct_answer)) {
                $newCorrectAnswer = $newLetter;
            }
        }

        return [
            'options' => $newOptions,
            'correct_answer' => $newCorrectAnswer,
        ];
    }

    /**
     * Reshuffle MCQs for session refresh
     * Returns NULL if session is locked (anti-cheating)
     */
    public function reshuffleMcqs(ExamSession $session)
    {
        if ($session->is_locked) {
            return null; // Session locked - cannot reshuffle
        }

        // Get current MCQ IDs from session
        $currentMcqIds = json_decode($session->mcq_sequence, true);

        // Return same MCQs in different order
        return Mcq::whereIn('id', $currentMcqIds)
            ->inRandomOrder()
            ->get();
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mcq;
use App\Models\Subject;

class McqSeeder extends Seeder
{
    public function run(): void
    {
        echo "\nCreating MCQs...\n";

        $subjects = Subject::all();

        if ($subjects->count() === 0) {
            echo "ERROR: No subjects found!\n";
            return;
        }

        $mcqs = [
            ['q' => 'What is 2+2?', 'a' => '3', 'b' => '4', 'c' => '5', 'd' => '6', 'ans' => 'B'],
            ['q' => 'What is the capital of France?', 'a' => 'London', 'b' => 'Paris', 'c' => 'Berlin', 'd' => 'Madrid', 'ans' => 'B'],
            ['q' => 'Which planet is closest to sun?', 'a' => 'Venus', 'b' => 'Mercury', 'c' => 'Earth', 'd' => 'Mars', 'ans' => 'B'],
            ['q' => 'What is H2O?', 'a' => 'Oxygen', 'b' => 'Water', 'c' => 'Hydrogen', 'd' => 'Salt', 'ans' => 'B'],
            ['q' => 'Who wrote Romeo and Juliet?', 'a' => 'Milton', 'b' => 'Marlowe', 'c' => 'Shakespeare', 'd' => 'Chaucer', 'ans' => 'C'],
            ['q' => 'What is the largest planet?', 'a' => 'Saturn', 'b' => 'Mars', 'c' => 'Jupiter', 'd' => 'Neptune', 'ans' => 'C'],
            ['q' => 'What is the chemical symbol for Gold?', 'a' => 'Go', 'b' => 'Gd', 'c' => 'Au', 'd' => 'Ag', 'ans' => 'C'],
            ['q' => 'How many bones in human body?', 'a' => '186', 'b' => '206', 'c' => '226', 'd' => '246', 'ans' => 'B'],
            ['q' => 'Speed of light is?', 'a' => '2×10^8', 'b' => '3×10^8', 'c' => '4×10^8', 'd' => '5×10^8', 'ans' => 'B'],
            ['q' => '15% of 200 is?', 'a' => '20', 'b' => '25', 'c' => '30', 'd' => '35', 'ans' => 'C'],
        ];

        $count = 0;

        foreach ($subjects as $subject) {
            foreach ($mcqs as $index => $mcq) {
                $exists = Mcq::where('subject_id', $subject->id)
                    ->where('question', $mcq['q'])
                    ->exists();

                if (!$exists) {
                    Mcq::create([
                        'subject_id' => $subject->id,
                        'question' => $mcq['q'],
                        'option_a' => $mcq['a'],
                        'option_b' => $mcq['b'],
                        'option_c' => $mcq['c'],
                        'option_d' => $mcq['d'],
                        'correct_answer' => $mcq['ans'],
                        'difficulty' => ['easy', 'medium', 'hard'][$index % 3],
                        'explanation' => 'Sample explanation for this question',
                        'status' => 'active',
                        'created_by' => 1,
                    ]);
                    $count++;
                }
            }
        }

        echo "✅ Created $count MCQs\n\n";
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Mcq;
use App\Models\TestPackage;
use Illuminate\Http\Request;

class ExamSelectionController extends Controller
{
    /**
     * Show exam selection page
     */
    public function index()
    {
        $subjects = Subject::where('status', 'active')
            ->withCount(['mcqs' => function ($q) {
                $q->where('status', 'active');
            }])
            ->get();

        $testPackages = TestPackage::where('is_active', true)->get();

        return view('exam.selection', compact('subjects', 'testPackages'));
    }

    /**
     * Create custom test
     */
    public function createCustomTest(Request $request)
    {
        $validated = $request->validate([
            'subjects' => 'required|array',
            'subjects.*.subject_id' => 'required|exists:subjects,id',
            'subjects.*.percentage' => 'required|integer|min:0|max:100',
            'subjects.*.easy' => 'required|integer|min:0',
            'subjects.*.medium' => 'required|integer|min:0',
            'subjects.*.hard' => 'required|integer|min:0',
            'total_questions' => 'required|integer|min:1',
        ]);

        // Generate questions
        $allQuestions = [];

        foreach ($validated['subjects'] as $subject) {
            $easy = Mcq::where('subject_id', $subject['subject_id'])
                ->where('difficulty', 'easy')
                ->where('status', 'active')
                ->inRandomOrder()
                ->limit($subject['easy'])
                ->get();

            $medium = Mcq::where('subject_id', $subject['subject_id'])
                ->where('difficulty', 'medium')
                ->where('status', 'active')
                ->inRandomOrder()
                ->limit($subject['medium'])
                ->get();

            $hard = Mcq::where('subject_id', $subject['subject_id'])
                ->where('difficulty', 'hard')
                ->where('status', 'active')
                ->inRandomOrder()
                ->limit($subject['hard'])
                ->get();

            $allQuestions = array_merge(
                $allQuestions,
                $easy->toArray(),
                $medium->toArray(),
                $hard->toArray()
            );
        }

        // Shuffle questions
        shuffle($allQuestions);

        session([
            'exam_mcqs' => $allQuestions,
            'exam_subject_id' => 'custom_test',
            'exam_started' => false,
            'exam_type' => 'custom',
        ]);

        return redirect()->route('exam.index', ['subject_id' => 'custom_test']);
    }

    /**
     * Load preset test (e.g., MDCAT)
     */
    public function loadPresetTest(TestPackage $testPackage)
    {
        $questions = $testPackage->questions;

        session([
            'exam_mcqs' => $questions->toArray(),
            'exam_subject_id' => 'preset_' . $testPackage->id,
            'exam_started' => false,
            'exam_type' => 'preset',
            'test_package_id' => $testPackage->id,
        ]);

        return redirect()->route('exam.index', ['subject_id' => 'preset_' . $testPackage->id]);
    }
}
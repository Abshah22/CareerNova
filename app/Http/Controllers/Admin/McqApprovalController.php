<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mcq;
use App\Models\Subject;
use Illuminate\Http\Request;

class McqApprovalController extends Controller
{
    /**
     * List MCQs for review
     */
    public function index()
    {
        $mcqs = Mcq::where('status', 'pending_review')
            ->with('subject', 'creator')
            ->latest()
            ->paginate(20);

        $stats = [
            'pending' => Mcq::where('status', 'pending_review')->count(),
            'approved' => Mcq::where('status', 'active')->count(),
            'rejected' => Mcq::where('status', 'inactive')->count(),
        ];

        return view('admin.mcq-approval.index', compact('mcqs', 'stats'));
    }

    /**
     * View MCQ details
     */
    public function show(Mcq $mcq)
    {
        return view('admin.mcq-approval.show', compact('mcq'));
    }

    /**
     * Approve MCQ
     */
    public function approve(Request $request, Mcq $mcq)
    {
        $mcq->update([
            'status' => 'active',
            'verified' => true,
            'needs_review' => false,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        \Log::info('MCQ approved', [
            'mcq_id' => $mcq->id,
            'approved_by' => auth()->user()->name,
        ]);

        return redirect()->route('admin.mcq-approval.index')
            ->with('success', 'MCQ approved successfully!');
    }

    /**
     * Reject MCQ
     */
    public function reject(Request $request, Mcq $mcq)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $mcq->update([
            'status' => 'inactive',
            'verified' => false,
            'needs_review' => true,
        ]);

        // Notify teacher
        \Log::info('MCQ rejected', [
            'mcq_id' => $mcq->id,
            'reason' => $validated['reason'],
        ]);

        return redirect()->route('admin.mcq-approval.index')
            ->with('success', 'MCQ rejected and teacher notified.');
    }

    /**
     * Bulk approve
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'mcq_ids' => 'required|array',
            'mcq_ids.*' => 'integer',
        ]);

        Mcq::whereIn('id', $validated['mcq_ids'])
            ->update([
                'status' => 'active',
                'verified' => true,
                'needs_review' => false,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

        return redirect()->back()
            ->with('success', count($validated['mcq_ids']) . ' MCQs approved!');
    }
}
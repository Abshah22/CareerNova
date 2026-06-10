@extends('layouts.app')

@section('title', 'Review MCQ')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <div class="mb-6">
                    <a href="{{ route('admin.mcq-approval.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">MCQ Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Subject:</strong> {{ $mcq->subject->name }}
                        </div>
                        <div class="mb-3">
                            <strong>Created By:</strong> {{ $mcq->creator->name }}
                        </div>
                        <div class="mb-3">
                            <strong>Difficulty:</strong>
                            <span class="badge
                                @if($mcq->difficulty === 'easy') bg-success
                                @elseif($mcq->difficulty === 'medium') bg-warning
                                @else bg-danger
                                @endif
                            ">
                                {{ ucfirst($mcq->difficulty) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Question</h5>
                    </div>
                    <div class="card-body">
                        <p class="fw-bold">{{ $mcq->question }}</p>

                        <div class="mt-4">
                            @foreach (['A', 'B', 'C', 'D'] as $option)
                                <div class="p-3 border rounded mb-2 {{ $mcq->correct_answer === $option ? 'bg-success bg-opacity-10 border-success' : '' }}">
                                    <strong>{{ $option }}.</strong> {{ $mcq->{'option_' . strtolower($option)} }}
                                    @if ($mcq->correct_answer === $option)
                                        <span class="badge bg-success float-end">Correct</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if ($mcq->explanation)
                            <div class="alert alert-info mt-4">
                                <strong>Explanation:</strong> {{ $mcq->explanation }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex gap-2">
                    <form action="{{ route('admin.mcq-approval.approve', $mcq) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-check"></i> Approve MCQ
                        </button>
                    </form>

                    <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fas fa-times"></i> Reject MCQ
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject MCQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.mcq-approval.reject', $mcq) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <label for="reason" class="form-label">Reason for Rejection</label>
                    <textarea name="reason" id="reason" class="form-control" rows="4" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
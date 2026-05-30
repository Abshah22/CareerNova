@extends('layouts.admin')

@section('title', 'Pending MCQ Reviews')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="h3 mb-0">
                <i class="fas fa-list-check me-2"></i>Pending MCQ Reviews
            </h2>
            <p class="text-muted small">Review and approve MCQs before they go live</p>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-warning">{{ $mcqs->count() }} Pending</span>
        </div>
    </div>

    @if($mcqs->isEmpty())
        <div class="alert alert-info" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Great!</strong> All MCQs are reviewed. No pending items.
        </div>
    @else
        <div class="row">
            @foreach($mcqs as $mcq)
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-gradient">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="card-title mb-0">
                                        <span class="badge bg-warning text-dark">{{ $mcq->subject->name ?? 'N/A' }}</span>
                                    </h6>
                                </div>
                                <div class="col-auto">
                                    <span class="badge bg-secondary">Pending Review</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Question -->
                            <div class="mb-3">
                                <h6 class="text-dark fw-bold">Question:</h6>
                                <p class="mb-0">{{ $mcq->question }}</p>
                            </div>

                            <!-- Options -->
                            <div class="mb-3">
                                <h6 class="text-dark fw-bold">Options:</h6>
                                <div class="list-group list-group-sm">
                                    <label class="list-group-item">
                                        <strong>A.</strong> {{ $mcq->option_a }}
                                    </label>
                                    <label class="list-group-item">
                                        <strong>B.</strong> {{ $mcq->option_b }}
                                    </label>
                                    <label class="list-group-item">
                                        <strong>C.</strong> {{ $mcq->option_c }}
                                    </label>
                                    <label class="list-group-item">
                                        <strong>D.</strong> {{ $mcq->option_d }}
                                    </label>
                                </div>
                            </div>

                            <!-- Correct Answer -->
                            <div class="mb-3">
                                <h6 class="text-dark fw-bold">Correct Answer:</h6>
                                <p class="mb-0">
                                    <span class="badge bg-success">Option {{ $mcq->correct_option }}</span>
                                </p>
                            </div>

                            <!-- Explanation -->
                            <div class="mb-3">
                                <h6 class="text-dark fw-bold">Explanation:</h6>
                                <p class="mb-0 small">{{ $mcq->explanation }}</p>
                            </div>

                            <!-- Metadata -->
                            <div class="row text-muted small mb-3">
                                <div class="col-6">
                                    <p class="mb-1"><strong>Difficulty:</strong> {{ $mcq->difficulty ?? 'N/A' }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Created by:</strong> {{ $mcq->creator->name ?? 'System' }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-0"><strong>Created:</strong> {{ $mcq->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light border-top">
                            <div class="row g-2">
                                <div class="col-6">
                                    <form action="{{ route('admin.mcqs.approve', $mcq->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success w-100">
                                            <i class="fas fa-check me-1"></i>Approve
                                        </button>
                                    </form>
                                </div>
                                <div class="col-6">
                                    <form action="{{ route('admin.mcqs.reject', $mcq->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-times me-1"></i>Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12">
                {{ $mcqs->links() }}
            </div>
        </div>
    @endif
</div>

<style>
    .card-header.bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .card-header.bg-gradient .card-title {
        color: white !important;
    }

    .list-group-item {
        padding: 0.75rem 0.75rem;
        border-left: 3px solid #e9ecef;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
        border-left-color: #667eea;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection

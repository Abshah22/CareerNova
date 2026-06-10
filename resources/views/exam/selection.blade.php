@extends('layouts.app')

@section('title', 'Select Exam Type')

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-3xl font-bold mb-8">
                <i class="fas fa-book"></i> Select Your Exam
            </h1>

            <!-- Preset Tests -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4">📋 Preset Tests</h2>
                <div class="row">
                    @foreach ($testPackages as $package)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $package->name }}</h5>
                                    <p class="card-text">{{ $package->description }}</p>
                                    <ul class="small text-muted">
                                        <li>{{ $package->total_questions }} Questions</li>
                                        <li>{{ $package->time_limit_minutes }} Minutes</li>
                                        <li>Passing: {{ $package->passing_percentage }}%</li>
                                    </ul>
                                    <form action="{{ route('exam.load-preset', $package) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-play"></i> Start Test
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <hr>

            <!-- Subject-wise Selection -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4">🎯 Custom Test</h2>
                <form id="custom-test-form" action="{{ route('exam.create-custom') }}" method="POST">
                    @csrf

                    <div class="row mb-4">
                        @foreach ($subjects as $subject)
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">{{ $subject->name }} ({{ $subject->mcqs_count }} Available)</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Percentage of Test (%)</label>
                                            <input type="number" name="subjects[{{ $subject->id }}][percentage]" 
                                                   class="form-control percentage-input" min="0" max="100" value="0"
                                                   data-subject="{{ $subject->id }}">
                                        </div>

                                        <div class="row">
                                            <div class="col-4">
                                                <label class="form-label">Easy</label>
                                                <input type="number" name="subjects[{{ $subject->id }}][easy]" 
                                                       class="form-control difficulty-input" min="0" value="0">
                                            </div>
                                            <div class="col-4">
                                                <label class="form-label">Medium</label>
                                                <input type="number" name="subjects[{{ $subject->id }}][medium]" 
                                                       class="form-control difficulty-input" min="0" value="0">
                                            </div>
                                            <div class="col-4">
                                                <label class="form-label">Hard</label>
                                                <input type="number" name="subjects[{{ $subject->id }}][hard]" 
                                                       class="form-control difficulty-input" min="0" value="0">
                                            </div>
                                        </div>

                                        <input type="hidden" name="subjects[{{ $subject->id }}][subject_id]" value="{{ $subject->id }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Total Questions</label>
                        <input type="number" name="total_questions" class="form-control" min="1" value="50" required>
                    </div>

                    <div class="alert alert-info">
                        <strong>ℹ️ Tip:</strong> Enter percentages for each subject. Questions will be auto-selected with balanced difficulty levels.
                    </div>

                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-play"></i> Start Custom Test
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Analytics - CareerNova Admin')

@section('content')
<div class="page-title">
    <i class="fas fa-chart-bar"></i>
    <h1 class="mb-0">Analytics Dashboard</h1>
</div>

<!-- Key Metrics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">Total Tests</p>
                        <h3 class="text-primary fw-bold">{{ $totalTests ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-graduation-cap text-primary" style="font-size: 2.5rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card" style="border-left-color: #48bb78;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">Total Students</p>
                        <h3 class="fw-bold" style="color: #48bb78;">{{ $totalStudents ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-users" style="font-size: 2.5rem; opacity: 0.2; color: #48bb78;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card" style="border-left-color: #ed8936;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">Average Score</p>
                        <h3 class="fw-bold" style="color: #ed8936;">{{ number_format($averageScore ?? 0, 2) }}%</h3>
                    </div>
                    <i class="fas fa-star" style="font-size: 2.5rem; opacity: 0.2; color: #ed8936;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card" style="border-left-color: #9f7aea;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">System Status</p>
                        <h3 class="fw-bold"><span class="badge bg-success">Operational</span></h3>
                    </div>
                    <i class="fas fa-server" style="font-size: 2.5rem; opacity: 0.2; color: #9f7aea;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaderboard Section -->
<div class="row mt-5">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0"><i class="fas fa-trophy text-warning me-2"></i>Top Performers</h5>
            </div>
            <div class="card-body">
                @if(isset($leaderboard) && count($leaderboard) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="10%">Rank</th>
                                    <th width="40%">Student Name</th>
                                    <th width="25%">Tests Taken</th>
                                    <th width="25%">Average Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaderboard as $index => $student)
                                <tr>
                                    <td>
                                        <span class="badge" style="background: 
                                            @if($index == 0) #ffd700
                                            @elseif($index == 1) #c0c0c0
                                            @elseif($index == 2) #cd7f32
                                            @else #667eea
                                            @endif">
                                            {{ $index + 1 }}
                                            @if($index == 0) 🥇
                                            @elseif($index == 1) 🥈
                                            @elseif($index == 2) 🥉
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $student['name'] ?? 'Unknown' }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $student['tests_taken'] ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $student['average_score'] ?? 0 }}%; background: linear-gradient(90deg, #667eea, #764ba2);"
                                                 aria-valuenow="{{ $student['average_score'] ?? 0 }}" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                                {{ number_format($student['average_score'] ?? 0, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">No student data available yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Quick Statistics</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <label class="text-muted small">Active Students</label>
                    <h4 class="fw-bold">{{ $totalStudents ?? 0 }}</h4>
                </div>
                <hr>
                <div class="mb-4">
                    <label class="text-muted small">Tests Completed</label>
                    <h4 class="fw-bold">{{ $totalTests ?? 0 }}</h4>
                </div>
                <hr>
                <div class="mb-4">
                    <label class="text-muted small">Average Performance</label>
                    <h4 class="fw-bold">{{ number_format($averageScore ?? 0, 1) }}%</h4>
                </div>
                <hr>
                <div>
                    <label class="text-muted small">Last Updated</label>
                    <p class="small">{{ now()->format('M d, Y H:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="card mt-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0"><i class="fas fa-heart text-danger me-2"></i>System Health</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span>Database</span>
                    <span class="badge bg-success">Connected</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Cache</span>
                    <span class="badge bg-success">Active</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>API</span>
                    <span class="badge bg-success">Operational</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Authentication</span>
                    <span class="badge bg-success">Enabled</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .page-title {
        color: #2d3748;
        font-weight: 600;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-title i {
        font-size: 2rem;
        color: #667eea;
    }

    .card {
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border-radius: 8px;
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .stat-card {
        border-left: 4px solid #667eea;
    }

    .table-hover tbody tr:hover {
        background-color: #f7fafc;
    }

    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
</style>
@endsection

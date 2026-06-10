@extends('layouts.app')

@section('title', 'MCQ Approval')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">
                        <i class="fas fa-check-square"></i> MCQ Approval
                    </h1>
                    <div class="flex gap-2">
                        <span class="badge bg-warning">{{ $stats['pending'] }} Pending</span>
                        <span class="badge bg-success">{{ $stats['approved'] }} Approved</span>
                        <span class="badge bg-danger">{{ $stats['rejected'] }} Rejected</span>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($mcqs->isEmpty())
                    <div class="alert alert-info">
                        No MCQs pending for approval.
                    </div>
                @else
                    <form id="approval-form" method="POST" action="{{ route('admin.mcq-approval.bulk-approve') }}">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" id="select-all" onclick="toggleAll(this)">
                                        </th>
                                        <th>Question</th>
                                        <th>Subject</th>
                                        <th>Difficulty</th>
                                        <th>Created By</th>
                                        <th>Submitted</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mcqs as $mcq)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="mcq_ids[]" value="{{ $mcq->id }}" class="mcq-checkbox">
                                            </td>
                                            <td>{{ Str::limit($mcq->question, 60) }}</td>
                                            <td>{{ $mcq->subject->name }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($mcq->difficulty === 'easy') bg-success
                                                    @elseif($mcq->difficulty === 'medium') bg-warning
                                                    @else bg-danger
                                                    @endif
                                                ">
                                                    {{ ucfirst($mcq->difficulty) }}
                                                </span>
                                            </td>
                                            <td>{{ $mcq->creator->name }}</td>
                                            <td>{{ $mcq->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.mcq-approval.show', $mcq) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Review
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex gap-2">
                            <button type="submit" class="btn btn-success" id="bulk-approve-btn" disabled>
                                <i class="fas fa-check"></i> Bulk Approve Selected
                            </button>
                        </div>
                    </form>

                    {{ $mcqs->links() }}
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function toggleAll(checkbox) {
    document.querySelectorAll('.mcq-checkbox').forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateBulkButton();
}

function updateBulkButton() {
    const checked = document.querySelectorAll('.mcq-checkbox:checked').length;
    document.getElementById('bulk-approve-btn').disabled = checked === 0;
}

document.querySelectorAll('.mcq-checkbox').forEach(cb => {
    cb.addEventListener('change', updateBulkButton);
});
</script>
@endsection
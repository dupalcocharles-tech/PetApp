@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Ban Appeals</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Clinic</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appeals as $appeal)
                            <tr>
                                <td>{{ $appeal->clinic?->clinic_name ?? 'Unknown Clinic' }}</td>
                                <td style="max-width: 260px;">
                                    <div class="small text-muted">
                                        {{ \Illuminate\Support\Str::limit($appeal->message, 140) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $appeal->status === 'pending' ? 'warning' : ($appeal->status === 'approved' ? 'success' : 'secondary') }}">
                                        {{ ucfirst($appeal->status) }}
                                    </span>
                                </td>
                                <td>{{ $appeal->created_at?->format('M d, Y H:i') }}</td>
                                <td class="text-end">
                                    @if($appeal->status === 'pending')
                                        <form method="POST" action="{{ route('admin.appeals.approve', $appeal->id) }}" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.appeals.reject', $appeal->id) }}" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-secondary">Reject</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    No appeals found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($appeals, 'links'))
            <div class="card-footer border-0">
                {{ $appeals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection


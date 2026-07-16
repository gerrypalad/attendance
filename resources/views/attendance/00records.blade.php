@extends('layouts.app_bs5') {{-- Adjust this to match your actual layout file --}}

@section('content')
<div class="container-fluid py-4">

    <!-- PAGE HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Attendance Records</h4>
            <p class="text-muted mb-0 small">View and filter employee attendance ledger logs.</p>
        </div>
        <a href="#" class="btn btn-light border rounded-3 shadow-sm">
            <i class="bi bi-arrow-left-circle me-2"></i> Back to Console
        </a>
    </div>

    <!-- FILTERS CARD -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('attendance.records') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted text-uppercase">Date From</label>
                    <input type="date" name="date_from" class="form-control rounded-3" value="{{ $filters['date_from'] }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted text-uppercase">Date To</label>
                    <input type="date" name="date_to" class="form-control rounded-3" value="{{ $filters['date_to'] }}">
                </div>

                {{-- EMPLOYEE SELECT (ADMIN ONLY) --}}
                @if($isAdmin)
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted text-uppercase">Employee</label>
                    <select name="user_id" class="form-select rounded-3">
                        <option value="">All Employees</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $filters['user_id'] == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- ACTION BUTTONS --}}
                <div class="col-md-3 {{ $isAdmin ? '' : 'offset-md-3' }}">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-3 flex-grow-1">
                            <i class="bi bi-funnel me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('attendance.records') }}" class="btn btn-light border rounded-3" title="Clear Filters">
                            <i class="bi bi-x-lg"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- RECORDS TABLE CARD -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold text-dark fs-6">
                <i class="bi bi-journal-text text-primary me-2"></i> Ledger Logs
            </h5>
            <span class="badge bg-light text-secondary border px-3 py-2">
                {{ $records->total() }} Total Records
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 small text-uppercase text-muted fw-semibold">Date</th>
                        @if($isAdmin)
                            <th class="py-3 small text-uppercase text-muted fw-semibold">Employee</th>
                        @endif
                        <th class="py-3 small text-uppercase text-muted fw-semibold">Time In</th>
                        <th class="py-3 small text-uppercase text-muted fw-semibold">Break Out</th>
                        <th class="py-3 small text-uppercase text-muted fw-semibold">Break In</th>
                        <th class="py-3 small text-uppercase text-muted fw-semibold">Time Out</th>
                        <th class="py-3 small text-uppercase text-muted fw-semibold text-center">Total Hours</th>
                        <th class="pe-4 py-3 small text-uppercase text-muted fw-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="fw-semibold text-dark">
                                    {{ \Carbon\Carbon::parse($record->work_date)->format('M d, Y') }}
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($record->work_date)->format('l') }}
                                </small>
                            </td>

                            @if($isAdmin)
                                <td class="py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <span class="fw-medium">{{ $record->user->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                            @endif

                            <td class="py-3">
                                <span class="fw-medium text-dark">
                                    {{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('g:i A') : '--:--' }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="fw-medium text-dark">
                                    {{ $record->break_out ? \Carbon\Carbon::parse($record->break_out)->format('g:i A') : '--:--' }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="fw-medium text-dark">
                                    {{ $record->break_in ? \Carbon\Carbon::parse($record->break_in)->format('g:i A') : '--:--' }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="fw-medium text-dark">
                                    {{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('g:i A') : '--:--' }}
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2">
                                    {{ number_format($record->total_hours ?? 0, 2) }} hrs
                                </span>
                            </td>

                            {{-- ACTIONS COLUMN --}}
                            <td class="pe-4 py-3 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Edit Button: Visible if user is admin OR if the record belongs to the authenticated user --}}
                                    @if($isAdmin || $record->user_id === Auth::id())
                                        <a href="{{ route('attendance.edit', $record->id) }}" class="btn btn-sm btn-light border text-primary" title="Edit Record">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endif

                                    {{-- Delete Button: Visible only to admin --}}
                                    @if($isAdmin)
                                        <form action="{{ route('attendance.delete', $record->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete Record">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isAdmin ? 8 : 7 }}" class="text-center py-5 bg-light">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    <p class="mb-0 fw-medium">No attendance records found.</p>
                                    <small>Try adjusting your filters or check back later.</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
</div>
@endsection

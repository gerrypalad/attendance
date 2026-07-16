@extends('layouts.app_bs5') {{-- Adjust this to match your actual layout file --}}

@section('content')
<div class="container-fluid py-4">

    <!-- PAGE HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Attendance Records</h4>
            <p class="text-muted mb-0 small">View and edit employee attendance records.</p>
        </div>
            <div class="d-flex gap-2">

                {{-- EXPORT TO PDF BUTTON --}}
                <a href="{{ route('attendance.export-pdf', $filters) }}" class="btn btn-sm btn-success rounded-3 shadow-sm" target="_blank">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Export to PDF
                </a>

                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary rounded-3 shadow-sm">
                    <i class="bi bi-arrow-left-circle me-1"></i> Back to Dashboard
                </a>

            </div>
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
                <i class="bi bi-journal-text text-primary me-2"></i> Attendance Records
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
                        {{-- @if($isAdmin) --}}
                            <th class="py-3 small text-uppercase text-muted fw-semibold">Employee</th>
                        {{-- @endif --}}
                        <th class="py-3 small text-uppercase text-muted fw-semibold">Time In</th>
                        <th class="py-3 small text-uppercase text-muted fw-semibold">Break Out</th>
                        <th class="py-3 small text-uppercase text-muted fw-semibold">Break In</th>
                        <th class="py-3 small text-uppercase text-muted fw-semibold">Time Out</th>
                        <th class="py-3 small text-uppercase text-muted fw-semibold text-center">Total Hours</th>
                        @if($isAdmin)
                        <th class="pe-4 py-3 small text-uppercase text-muted fw-semibold text-center">Actions</th>
                        @endif
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

                            {{-- @if($isAdmin) --}}
                                <td class="py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <span class="fw-medium">{{ $record->user->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                            {{-- @endif --}}

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
                                <span class="badge bg-info-subtle text-dark border border-info-subtle px-3 py-2">
                                    {{ number_format($record->total_hours ?? 0, 2) }} hrs
                                </span>
                            </td>

                            {{-- ACTIONS COLUMN --}}
                          @if($isAdmin || Auth::id() === 4)
                            <td class="pe-4 py-3 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Edit Button --}}

                                        <button type="button" class="btn btn-sm btn-light border text-primary btn-edit"
                                            data-bs-toggle="modal" data-bs-target="#editAttendanceModal"
                                            data-update-url="{{ route('attendance.update', $record->id) }}"
                                            data-time-in="{{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('H:i') : '' }}"
                                            data-break-out="{{ $record->break_out ? \Carbon\Carbon::parse($record->break_out)->format('H:i') : '' }}"
                                            data-break-in="{{ $record->break_in ? \Carbon\Carbon::parse($record->break_in)->format('H:i') : '' }}"
                                            data-time-out="{{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('H:i') : '' }}"
                                            data-remarks="{{ $record->remarks ?? '' }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                          @endif

                                    {{-- Delete Button --}}
                                    @if($isAdmin && Auth::id() === 12)
                                        <button type="button" class="btn btn-sm btn-light border text-danger btn-delete"
                                            data-bs-toggle="modal" data-bs-target="#deleteAttendanceModal"
                                            data-delete-url="{{ route('attendance.delete', $record->id) }}"
                                            data-date="{{ \Carbon\Carbon::parse($record->work_date)->format('M d, Y') }}">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                </div>
                            </td>
                            @endif
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

        @if($records->hasPages())
            <div class="card-footer bg-white border-top py-3 px-4 rounded-bottom-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
                    <small class="text-muted">
                        Showing {{ $records->firstItem() }} to {{ $records->lastItem() }} of {{ $records->total() }} results
                    </small>
                    <nav class="ms-auto">
                        {{ $records->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            </div>
        @endif

</div>

<!-- EDIT ATTENDANCE MODAL -->
<div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow">
            <form id="form-edit-attendance" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-0 px-4 pt-4">
                    <h5 class="modal-title fw-semibold">
                        <i class="bi bi-pencil-square text-primary me-2"></i> Edit Attendance Record
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted text-uppercase">Time In</label>
                            <input type="time" name="time_in" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted text-uppercase">Break Out</label>
                            <input type="time" name="break_out" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted text-uppercase">Break In</label>
                            <input type="time" name="break_in" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted text-uppercase">Time Out</label>
                            <input type="time" name="time_out" class="form-control rounded-3">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted text-uppercase">Remarks</label>
                            <textarea name="remarks" class="form-control rounded-3" rows="3" placeholder="Add any notes or remarks..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-3">
                        <i class="bi bi-check-circle me-1"></i> Update Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DELETE ATTENDANCE MODAL -->
<div class="modal fade" id="deleteAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form id="form-delete-attendance" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header border-0 px-4 pt-4">
                    <h5 class="modal-title fw-semibold text-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <p class="text-muted mb-0">
                        Are you sure you want to delete the attendance record for
                        <strong id="delete-record-date" class="text-dark"></strong>?
                        This action cannot be undone.
                    </p>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-3">
                        <i class="bi bi-trash3 me-1"></i> Yes, Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        /*
        |--------------------------------------------------------------------------
        | Edit Modal Handler
        |--------------------------------------------------------------------------
        */
        const editModal = document.getElementById('editAttendanceModal');
        if (editModal) {
            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                // Extract data from button attributes
                const updateUrl = button.getAttribute('data-update-url');
                const timeIn = button.getAttribute('data-time-in');
                const breakOut = button.getAttribute('data-break-out');
                const breakIn = button.getAttribute('data-break-in');
                const timeOut = button.getAttribute('data-time-out');
                const remarks = button.getAttribute('data-remarks');

                // Set form action URL using the named route passed via data attribute
                const form = editModal.querySelector('#form-edit-attendance');
                form.action = updateUrl;

                // Populate inputs
                form.querySelector('[name="time_in"]').value = timeIn;
                form.querySelector('[name="break_out"]').value = breakOut;
                form.querySelector('[name="break_in"]').value = breakIn;
                form.querySelector('[name="time_out"]').value = timeOut;
                form.querySelector('[name="remarks"]').value = remarks;
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Modal Handler
        |--------------------------------------------------------------------------
        */
        const deleteModal = document.getElementById('deleteAttendanceModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                // Extract data from button attributes
                const deleteUrl = button.getAttribute('data-delete-url');
                const date = button.getAttribute('data-date');

                // Set form action URL using the named route passed via data attribute
                const form = deleteModal.querySelector('#form-delete-attendance');
                form.action = deleteUrl;

                // Populate confirmation text
                deleteModal.querySelector('#delete-record-date').innerText = date;
            });
        }
    });
</script>

@endsection

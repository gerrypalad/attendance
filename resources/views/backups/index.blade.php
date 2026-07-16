@extends('layouts.app_bs5')
@section('title', 'Database Backups')

@section('content')
<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="bi bi-shield-lock text-primary me-2"></i> Database Backups
            </h4>
            <p class="text-muted mb-0 small">Create, download, and manage SQL database backups.</p>
        </div>
        <form action="{{ route('backups.store') }}" method="POST" class="d-inline"
              onsubmit="return confirm('Create a new database backup now?');">
            @csrf
            <button type="submit" class="btn btn-primary rounded-3 shadow-sm">
                <i class="bi bi-cloud-download me-1"></i> Create Backup
            </button>
        </form>
    </div>

    <!-- BACKUPS TABLE -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold text-dark fs-6">
                <i class="bi bi-archive text-primary me-2"></i> Available Backups
            </h5>
            <span class="badge bg-light text-secondary border px-3 py-2">
                {{ count($backups) }} File(s)
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 small text-uppercase text-muted fw-semibold">Filename</th>
                        <th class="py-3 small text-uppercase text-muted fw-semibold">Size</th>
                        <th class="py-3 small text-uppercase text-muted fw-semibold">Created</th>
                        <th class="pe-4 py-3 small text-uppercase text-muted fw-semibold text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($backups as $backup)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-zip text-primary fs-5 me-2"></i>
                                    <span class="fw-semibold text-dark">{{ $backup['filename'] }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-muted">
                                <span class="badge bg-light border text-secondary">{{ $backup['size'] }}</span>
                            </td>
                            <td class="py-3 text-muted">
                                <i class="bi bi-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($backup['created'])->diffForHumans() }}
                                <br>
                                <small class="text-muted-50">{{ $backup['created'] }}</small>
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('backups.download', $backup['filename']) }}"
                                       class="btn btn-sm btn-light border text-success" title="Download">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <form action="{{ route('backups.destroy', $backup['filename']) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Permanently delete this backup? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 bg-light">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    <p class="mb-0 fw-medium">No backups yet.</p>
                                    <small>Click "Create Backup" to generate your first database backup.</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(count($backups) > 0)
            <div class="card-footer bg-white border-top py-3 px-4 rounded-bottom-4">
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Backups are stored in <code>storage/app/backups/</code>. Keep this directory secure and backed up externally.
                </small>
            </div>
        @endif
    </div>
</div>
@endsection

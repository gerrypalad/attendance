@extends('layouts.app_bs5') {{-- Adjust this to match your actual layout file --}}

@section('content')
<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">{{ $title }}</h4>
            <p class="text-muted mb-0 small">Real-time tracking of employee attendance and shift status.</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-light text-secondary border px-3 py-2">
                <i class="bi bi-calendar-event me-1"></i>
                <span id="monitor-date">{{ \Carbon\Carbon::parse($today)->format('l, d F Y') }}</span>
            </span>
            <span class="badge bg-light text-secondary border px-3 py-2">
                <i class="bi bi-clock me-1"></i>
                <span id="monitor-clock">--:--:--</span>
            </span>
            <span class="badge bg-primary-subtle text-primary border px-3 py-2" id="refresh-indicator">
                <i class="bi bi-arrow-repeat me-1"></i> Live
            </span>
        </div>
    </div>

    <!-- SUMMARY STATS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-primary bg-primary bg-opacity-10 rounded-3 p-2">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-uppercase text-muted fw-semibold d-block" style="font-size: 0.7rem;">Total Staff</small>
                        <h5 class="fw-bold text-dark mb-0" id="stat-total">0</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-success bg-success bg-opacity-10 rounded-3 p-2">
                        <i class="bi bi-briefcase-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-uppercase text-muted fw-semibold d-block" style="font-size: 0.7rem;">Working</small>
                        <h5 class="fw-bold text-success mb-0" id="stat-working">0</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-warning bg-warning bg-opacity-10 rounded-3 p-2">
                        <i class="bi bi-cup-hot-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-uppercase text-muted fw-semibold d-block" style="font-size: 0.7rem;">On Break</small>
                        <h5 class="fw-bold text-warning mb-0" id="stat-break">0</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-secondary bg-secondary bg-opacity-10 rounded-3 p-2">
                        <i class="bi bi-check-circle-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-uppercase text-muted fw-semibold d-block" style="font-size: 0.7rem;">Completed</small>
                        <h5 class="fw-bold text-secondary mb-0" id="stat-completed">0</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN BOARD -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold text-dark fs-6">
                <i class="bi bi-people-fill text-primary me-2"></i> Employee Status Board
            </h5>
            <span class="badge bg-light text-secondary border px-3 py-2" id="total-users-badge">
                0 Total Users
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 small text-uppercase text-muted fw-semibold">Employee</th>
                        <th class="py-3 small text-uppercase text-muted fw-semibold">Status</th>
                        <th class="py-3 small text-uppercase text-muted fw-semibold">Time In</th>
                        <th class="py-3 small text-uppercase text-muted fw-semibold">Break Out</th>
                        <th class="py-3 small text-uppercase text-muted fw-semibold">Break In</th>
                        <th class="py-3 small text-uppercase text-muted fw-semibold">Time Out</th>
                        <th class="pe-4 py-3 small text-uppercase text-muted fw-semibold text-center">Total Hours</th>
                    </tr>
                </thead>
                <tbody id="employee-table-body">
                    <!-- Initial Loading State -->
                    <tr>
                        <td colspan="7" class="text-center py-5 bg-light">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-3 mb-0 fw-medium">Loading attendance data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Pulsing animation for the "Live" indicator */
    @keyframes pulse-live {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    #refresh-indicator {
        animation: pulse-live 2s infinite;
    }

    /* Monospace font for the live digital timers */
    .live-timer {
        font-family: monospace;
        letter-spacing: 1px;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monitorUrl = "{{ route('attendance.monitor-data') }}";

        /*
        |--------------------------------------------------------------------------
        | Header Digital Clock
        |--------------------------------------------------------------------------
        */
        function updateClock() {
            const now = new Date();
            let hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12;
            hours = hours ? hours : 12;
            hours = String(hours).padStart(2, '0');

            const clockEl = document.getElementById('monitor-clock');
            if(clockEl) clockEl.innerText = `${hours}:${minutes}:${seconds} ${ampm}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        /*
        |--------------------------------------------------------------------------
        | Fetch & Render Monitor Data (Updates every 15s)
        |--------------------------------------------------------------------------
        */
        function fetchData() {
            fetch(monitorUrl)
                .then(response => {
                    if (!response.ok) throw new Error(`Server returned status ${response.status}`);
                    return response.json();
                })
                .then(result => {
                    document.getElementById('monitor-date').innerText = result.date;
                    document.getElementById('total-users-badge').innerText = `${result.total_users} Total Users`;

                    let total = result.total_users;
                    let working = 0, onBreak = 0, completed = 0;

                    result.data.forEach(emp => {
                        if(emp.status === 'working') working++;
                        else if(emp.status === 'on_break') onBreak++;
                        else if(emp.status === 'shift_end') completed++;
                    });

                    document.getElementById('stat-total').innerText = total;
                    document.getElementById('stat-working').innerText = working;
                    document.getElementById('stat-break').innerText = onBreak;
                    document.getElementById('stat-completed').innerText = completed;

                    const tbody = document.getElementById('employee-table-body');
                    tbody.innerHTML = '';

                    if(result.data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-5 bg-light"><p class="text-muted mb-0 fw-medium">No employees found.</p></td></tr>`;
                        return;
                    }

                    result.data.forEach(emp => {
                        let statusBadge = '';
                        let timerAttr = '';

                        if(emp.status === 'working') {
                            statusBadge = `<span class="badge bg-success-subtle text-dark px-3 py-2"><i class="bi bi-circle-fill me-1"></i> Working</span>`;
                            timerAttr = `data-start="${emp.raw_time_in}" data-break-out="${emp.raw_break_out}" data-break-in="${emp.raw_break_in}"`;
                        } else if(emp.status === 'on_break') {
                            statusBadge = `<span class="badge bg-warning-subtle text-dark px-3 py-2"><i class="bi bi-pause-circle me-1"></i> On Break</span>`;
                            timerAttr = `data-start="${emp.raw_time_in}" data-break-out="${emp.raw_break_out}" data-break-in="${emp.raw_break_in}"`;
                        } else if(emp.status === 'shift_end') {
                            statusBadge = `<span class="badge bg-secondary-subtle text-dark px-3 py-2"><i class="bi bi-box-arrow-right me-1"></i> Timed Out</span>`;
                        } else {
                            statusBadge = `<span class="badge bg-light text-muted px-3 py-2"><i class="bi bi-circle me-1"></i> Off Duty</span>`;
                        }

                        const row = `
                            <tr>
                                <td class="ps-4 py-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <span class="fw-semibold text-dark text-nowrap">${emp.username}</span>
                                    </div>
                                </td>
                                <td class="py-2">
                                    <div class="d-flex align-items-center gap-2 flex-nowrap">
                                        ${statusBadge}
                                        <span class="live-timer badge bg-warning-subtle text-dark px-3 py-2" ${timerAttr}>00:00:00</span>
                                    </div>
                                </td>
                                <td class="py-2 fw-medium text-dark text-nowrap">${emp.time_in}</td>
                                <td class="py-2 fw-medium text-dark text-nowrap">${emp.break_out}</td>
                                <td class="py-2 fw-medium text-dark text-nowrap">${emp.break_in}</td>
                                <td class="py-2 fw-medium text-dark text-nowrap">${emp.time_out}</td>
                                <td class="pe-4 py-2 text-center">
                                    <span class="badge bg-info-subtle text-dark px-3 py-2 text-nowrap">
                                        ${emp.total_hours} hrs
                                    </span>
                                </td>
                            </tr>
                        `;
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                })
                .catch(err => {
                    console.error('Monitor Fetch Error:', err);
                    const tbody = document.getElementById('employee-table-body');
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center py-5 bg-light text-danger">
                                <i class="bi bi-exclamation-triangle-fill fs-1 d-block mb-2"></i>
                                <p class="fw-bold mb-1">Failed to load attendance data.</p>
                                <small class="text-muted">Check your browser console (Press F12) for details.</small>
                            </td>
                        </tr>
                    `;
                });
        }

        /*
        |--------------------------------------------------------------------------
        | Live Status Timer Engine (Updates every 1s)
        |--------------------------------------------------------------------------
        */
        function updateTimers() {
            document.querySelectorAll('.live-timer[data-start]').forEach(el => {
                let start = el.getAttribute('data-start');
                let breakOut = el.getAttribute('data-break-out');
                let breakIn = el.getAttribute('data-break-in');

                // Clean null strings passed from PHP
                if (start === 'null' || start === '') start = null;
                if (breakOut === 'null' || breakOut === '') breakOut = null;
                if (breakIn === 'null' || breakIn === '') breakIn = null;

                if(!start) { el.style.display = 'none'; return; }
                el.style.display = 'inline-block';

                const startTime = new Date(start).getTime();
                if (isNaN(startTime)) { el.innerText = '00:00:00'; return; }

                const now = new Date().getTime();
                let deduction = 0;

                if(breakOut && breakIn) {
                    const boTime = new Date(breakOut).getTime();
                    const biTime = new Date(breakIn).getTime();
                    if (!isNaN(boTime) && !isNaN(biTime)) deduction = biTime - boTime;
                } else if(breakOut && !breakIn) {
                    const boTime = new Date(breakOut).getTime();
                    if (!isNaN(boTime)) deduction = now - boTime;
                }

                let elapsed = now - startTime - deduction;
                if(elapsed < 0) elapsed = 0;

                const hours = Math.floor(elapsed / 3600000);
                const minutes = Math.floor((elapsed % 3600000) / 60000);
                const seconds = Math.floor((elapsed % 60000) / 1000);
                const pad = val => String(val).padStart(2, '0');

                el.innerText = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
            });
        }

        // Initial load
        fetchData();

        // Refresh table data & Total Hours every 15 seconds
        setInterval(fetchData, 15000);

        // Update live status timers every 1 second
        setInterval(updateTimers, 1000);
    });
</script>

@endsection

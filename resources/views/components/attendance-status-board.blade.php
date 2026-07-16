@props(['refreshInterval' => 15000])

<!-- EMPLOYEE STATUS BOARD -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom border-top py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold text-dark fs-6">
            <i class="bi bi-people-fill text-primary me-2"></i> Attendance Monitor Board
        </h5>
        <span class="badge bg-light text-secondary border px-3 py-2" id="total-users-badge">
            0 Total Employees
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-2 small text-uppercase text-muted fw-semibold">Employee</th>
                    <th class="py-2 small text-uppercase text-muted fw-semibold">Status</th>
                    <th class="py-2 small text-uppercase text-muted fw-semibold">Time In</th>
                    <th class="py-2 small text-uppercase text-muted fw-semibold">Break Out</th>
                    <th class="py-2 small text-uppercase text-muted fw-semibold">Break In</th>
                    <th class="py-2 small text-uppercase text-muted fw-semibold">Time Out</th>
                    <th class="pe-4 py-2 small text-uppercase text-muted fw-semibold text-center">Total Hours</th>
                </tr>
            </thead>
            <tbody id="employee-table-body">
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

<style>
    .live-timer {
        font-family: monospace;
        letter-spacing: 1px;
    }
</style>

<script>
    (function() {
        const REFRESH_INTERVAL = {{ (int) $refreshInterval }};
        const monitorUrl = "{{ route('attendance.monitor-data') }}";
        const componentId = 'cmp-' + Math.random().toString(36).substr(2, 9);

        // Scope selectors to this specific component instance
        const card = document.currentScript.parentElement;
        card.setAttribute('data-cmp-id', componentId);

        const tbody = card.querySelector('#employee-table-body');
        const badge = card.querySelector('#total-users-badge');

        function fetchData() {
            fetch(monitorUrl)
                .then(response => {
                    if (!response.ok) throw new Error(`Server returned status ${response.status}`);
                    return response.json();
                })
                .then(result => {
                    badge.innerText = `${result.total_users} Total Employees`;
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
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center py-5 bg-light text-danger">
                                <i class="bi bi-exclamation-triangle-fill fs-1 d-block mb-2"></i>
                                <p class="fw-bold mb-1">Failed to load attendance data.</p>
                                <small class="text-muted">Check console for details.</small>
                            </td>
                        </tr>
                    `;
                });
        }

        function updateTimers() {
            card.querySelectorAll('.live-timer[data-start]').forEach(el => {
                let start = el.getAttribute('data-start');
                let breakOut = el.getAttribute('data-break-out');
                let breakIn = el.getAttribute('data-break-in');

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

        fetchData();
        setInterval(fetchData, REFRESH_INTERVAL);
        setInterval(updateTimers, 1000);
    })();
</script>

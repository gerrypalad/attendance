@props(['attendance', 'status'])

    <!-- HEADER -->
    <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
          <!-- TITLE -->
          <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="text-primary fs-3">
                <i class="bi bi-calendar-check"></i>
            </div>

            <h5 class="mb-0 fw-semibold text-dark fs-3">
                ATTENDANCE CONSOLE • 
            </h5>

            <span class="text-danger fw-semibold fs-3">
                {{ \Carbon\Carbon::today()->format('F d, Y • l') }}
            </span>

              <!-- DIGITAL CLOCK -->
              {{-- <span class="badge bg-light text-secondary border fw-normal small px-2 py-1">
                  <i class="bi bi-clock me-1"></i>
                  <span id="widget-digital-clock">
                      --:--:--
                  </span>
              </span> --}}
          </div>

            <!-- STATUS -->
            <div class="d-flex align-items-center gap-2">
                @if ($status === 'not_clocked_in')
                    <span class="badge bg-secondary-subtle text-secondary border px-3 py-2">
                        <i class="bi bi-circle me-1"></i>
                        Off Duty
                    </span>
                @elseif ($status === 'working')
                    <span class="badge bg-success-subtle text-success border px-3 py-2">
                        <i class="bi bi-circle-fill me-1"></i>
                        On Duty
                    </span>

                @elseif ($status === 'on_break')
                    <span class="badge bg-warning-subtle text-warning border px-3 py-2">
                        <i class="bi bi-pause-circle me-1"></i>
                        On Break
                    </span>
                @endif

                @if ($status === 'working' && isset($attendance->time_in))
                    <span
                        id="live-runtime-counter"
                        data-start="{{ \Carbon\Carbon::parse($attendance->time_in)->toIso8601String() }}"
                        class="badge bg-dark text-white px-3 py-2">
                        00:00:00
                    </span>
                @endif

            </div>
        </div>
    </div>

    <!-- BODY -->
    <div class="card-body p-4">
        <div class="row g-4">
            <!-- TIME IN -->
            <div class="col-xl col-lg-4 col-md-6">
                <form
                    id="form-clock-in"
                    action="{{ route('attendance.clock-in') }}"
                    method="POST"
                    onsubmit="triggerModal(event,'Confirm Time In','Start your shift?','form-clock-in')">
                    @csrf

                    <button
                        type="submit"
                        {{-- UPDATED: Added check for time_out to prevent re-clocking in after shift ends --}}
                        {{ ($status !== 'not_clocked_in' || !empty($attendance->time_out)) ? 'disabled' : '' }}
                        class="card border-0 shadow-sm rounded-4 w-100 h-100 text-start p-4 attendance-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-uppercase text-muted fw-semibold">
                                    Time In
                                </small>
                                <h3 class="fw-bold text-dark mt-2 mb-0">
                                    {{ !empty($attendance->time_in)
                                        ? \Carbon\Carbon::parse($attendance->time_in)->format('g:i A')
                                        : '--:--'
                                    }}
                                </h3>
                            </div>
                            <div class="fs-2 text-primary">
                                <i class="bi bi-play-circle-fill"></i>
                            </div>
                        </div>
                    </button>
                </form>
            </div>

            <!-- BREAK OUT -->
            <div class="col-xl col-lg-4 col-md-6">
                <form
                    id="form-break-out"
                    action="{{ route('attendance.break-out') }}"
                    method="POST"
                    onsubmit="triggerModal(event,'Confirm Break Out','Go on break?','form-break-out')">
                    @csrf

                    <button
                        type="submit"
                        {{ ($status !== 'working' || !empty($attendance->break_out)) ? 'disabled' : '' }}
                        class="card border-0 shadow-sm rounded-4 w-100 h-100 text-start p-4 attendance-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-uppercase text-muted fw-semibold">
                                    Break Out
                                </small>
                                <h3 class="fw-bold text-dark mt-2 mb-0">
                                    {{ !empty($attendance->break_out)
                                        ? \Carbon\Carbon::parse($attendance->break_out)->format('g:i A')
                                        : '--:--'
                                    }}
                                </h3>
                            </div>

                            <div class="fs-2 text-warning">
                                <i class="bi bi-cup-hot-fill"></i>
                            </div>
                        </div>
                    </button>
                </form>
            </div>
                        <!-- BREAK IN -->
                        <div class="col-xl col-lg-4 col-md-6">
                            <form
                                id="form-break-in"
                                action="{{ route('attendance.break-in') }}"
                                method="POST"
                                onsubmit="triggerModal(event,'Confirm Break In','Resume work?','form-break-in')">
                                @csrf
                                <button
                                    type="submit"
                                    {{ $status !== 'on_break' ? 'disabled' : '' }}
                                    class="card border-0 shadow-sm rounded-4 w-100 h-100 text-start p-4 attendance-card">

                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <small class="text-uppercase text-muted fw-semibold">
                                                Break In
                                            </small>
                                            <h3 class="fw-bold text-dark mt-2 mb-0">
                                                {{ !empty($attendance->break_in)
                                                    ? \Carbon\Carbon::parse($attendance->break_in)->format('g:i A')
                                                    : '--:--'
                                                }}
                                            </h3>
                                        </div>

                                        <div class="fs-2 text-success">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </div>
                                    </div>
                                </button>
                            </form>
                        </div>

                        <!-- TIME OUT -->
                        <div class="col-xl col-lg-4 col-md-6">
                            <form
                                id="form-clock-out"
                                action="{{ route('attendance.clock-out') }}"
                                method="POST"
                                onsubmit="triggerModal(event,'Confirm Time Out','End your shift?','form-clock-out')">
                                @csrf
                                <button
                                    type="submit"
                                    {{ $status !== 'working' ? 'disabled' : '' }}
                                    class="card border-0 shadow-sm rounded-4 w-100 h-100 text-start p-4 attendance-card">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <small class="text-uppercase text-muted fw-semibold">
                                                Time Out
                                            </small>
                                            <h3 class="fw-bold text-dark mt-2 mb-0">
                                                {{ !empty($attendance->time_out)
                                                    ? \Carbon\Carbon::parse($attendance->time_out)->format('g:i A')
                                                    : '--:--'
                                                }}
                                            </h3>
                                        </div>
                                        <div class="fs-2 text-danger">
                                            <i class="bi bi-box-arrow-right"></i>
                                        </div>
                                    </div>
                                </button>
                            </form>
                        </div>

                        <!-- TOTAL HOURS -->
                        <div class="col-xl col-lg-4 col-md-6">
                            <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <small class="text-uppercase text-muted fw-semibold">
                                            Total Hours
                                        </small>
                                        <h3 class="fw-bold text-dark mt-2 mb-0">
                                            {{ number_format(($attendance->total_hours ?? 0.00), 2) }}
                                            <span class="fs-6 text-muted fw-normal">
                                                hrs
                                            </span>
                                        </h3>
                                    </div>

                                    <div class="fs-2 text-info">
                                        <i class="bi bi-stopwatch-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            <style>
                .attendance-card {
                    transition: all .25s ease;
                    cursor:pointer;
                }
                .attendance-card:hover {
                    transform: translateY(-4px);
                    box-shadow:0 .75rem 1.5rem rgba(15,23,42,.08)!important;
                }
                .attendance-card:disabled {
                    cursor:not-allowed;
                    /* opacity:.55; */
                }
            </style>

            <!-- CONFIRMATION MODAL -->
            <div class="modal fade"
                 id="custom-confirm-modal"
                 tabindex="-1"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow">
                        <div class="modal-header border-0 px-4 pt-4">
                            <h5 class="modal-title fw-semibold"
                                id="modal-title">
                                Confirm Action
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body px-4">
                            <p class="text-muted mb-0"
                               id="modal-description">
                                Are you sure you want to continue?
                            </p>
                        </div>
                        <div class="modal-footer border-0 px-4 pb-4">
                            <button
                                type="button"
                                class="btn btn-light rounded-3"
                                data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button
                                type="button"
                                id="modal-confirm-btn"
                                class="btn btn-primary rounded-3">
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                /*
                |--------------------------------------------------------------------------
                | Bootstrap Confirmation Modal Engine
                |--------------------------------------------------------------------------
                */
                let activeTargetFormId = null;
                function triggerModal(event, title, message, formId)
                {
                    event.preventDefault();
                    activeTargetFormId = formId;
                    const titleEl = document.getElementById('modal-title');
                    const descEl = document.getElementById('modal-description');

                    if(titleEl)
                    {
                        titleEl.innerText = title;
                    }

                    if(descEl)
                    {
                        descEl.innerText = message;
                    }

                    const modalElement =
                        document.getElementById('custom-confirm-modal');

                    if(modalElement)
                    {
                        const modal =
                            bootstrap.Modal.getOrCreateInstance(modalElement);
                        modal.show();
                    }
                }
                document.addEventListener('DOMContentLoaded', function(){
                    const confirmBtn =
                        document.getElementById('modal-confirm-btn');

                    if(confirmBtn)
                    {
                        confirmBtn.onclick = function(){
                            if(activeTargetFormId)
                            {
                                const form =
                                    document.getElementById(activeTargetFormId);
                                if(form)
                                {
                                    form.submit();
                                }
                            }
                        };
                    }
                });

                /*
                |--------------------------------------------------------------------------
                | Live Runtime Counter
                |--------------------------------------------------------------------------
                */
                (function(){
                    const timeInValue =
                        "{{ !empty($attendance->time_in)

                            ? \Carbon\Carbon::parse($attendance->time_in)->toIso8601String()
                            : ''
                        }}";

                    const statusValue = "{{ $status }}";

                    if(statusValue !== 'working' || !timeInValue)
                    {
                      return;
                    }

                    const timeInEpoch =
                        new Date(timeInValue).getTime();

                    const breakOutTime =
                        "{{ !empty($attendance->break_out)

                            ? \Carbon\Carbon::parse($attendance->break_out)->toIso8601String()

                            : ''
                        }}";

                    const breakInTime =
                        "{{ !empty($attendance->break_in)

                            ? \Carbon\Carbon::parse($attendance->break_in)->toIso8601String()

                            : ''
                        }}";

                    let breakDeduction = 0;

                    if(breakOutTime && breakInTime)

                    {
                        const breakStart =
                            new Date(breakOutTime).getTime();
                        const breakEnd =
                            new Date(breakInTime).getTime();
                        breakDeduction =
                            breakEnd - breakStart;
                    }
                    function updateRuntime(){
                        const now =
                            new Date().getTime();
                        let elapsed =
                            now - timeInEpoch;
                        elapsed -= breakDeduction;
                        if(elapsed < 0)
                        {
                            elapsed = 0;
                        }
                        const hours =
                            Math.floor(elapsed / 3600000);
                        const minutes =
                            Math.floor((elapsed % 3600000) / 60000);
                        const seconds =
                            Math.floor((elapsed % 60000) / 1000);
                        const pad =
                            value => String(value).padStart(2,'0');
                        const counter =
                            document.getElementById(
                                'live-runtime-counter'
                            );
                        if(counter)
                        {
                            counter.innerText =
                                `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
                        }
                    }
                    updateRuntime();
                    setInterval(updateRuntime,1000);
                })();

                /*
                |--------------------------------------------------------------------------
                | Header Digital Clock
                |--------------------------------------------------------------------------
                */
                // (function(){
                //     function updateClock(){
                //         const now =
                //             new Date();
                //         let hours =
                //             now.getHours();
                //
                //         const minutes =
                //             String(now.getMinutes())
                //             .padStart(2,'0');
                //
                //         const seconds =
                //             String(now.getSeconds())
                //             .padStart(2,'0');
                //
                //         const ampm =
                //             hours >= 12 ? 'PM' : 'AM';
                //
                //         hours =
                //             hours % 12;
                //         hours =
                //             hours ? hours : 12;
                //         hours =
                //             String(hours)
                //             .padStart(2,'0');
                //
                //         const clock =
                //             document.getElementById(
                //                 'widget-digital-clock'
                //             );
                //
                //         if(clock)
                //         {
                //             clock.innerText =
                //             `${hours}:${minutes}:${seconds} ${ampm}`;
                //         }
                //     }
                //     updateClock();
                //     setInterval(updateClock,1000);
                // })();
            </script>

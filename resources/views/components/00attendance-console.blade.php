@props(['attendance', 'status'])

<div class="bg-slate-200 border border-slate-200 shadow-sm rounded">
    <!-- Header -->
    <div class="px-5 py-2 border-b border-slate-200 bg-slate-50">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <i class="bi bi-calendar-check text-blue-700 text-lg"></i>
                <h3 class="font-semibold text-neutral-950 flex items-center gap-2">
                    <span>ATTENDANCE CONSOLE:</span>
                    <span class="text-sm font-bold text-rose-500">
                        {{ \Carbon\Carbon::today()->format('F d, Y : l') }}
                    </span>
                    <!-- Live Ticking Digital Clock -->
                    <span class="text-sm font-sans font-bold text-neutral-500 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 flex items-center gap-1">
                        <i class="bi bi-clock"></i>
                        <span id="widget-digital-clock">--:--:--</span>
                    </span>
                </h3>
            </div>

            <div class="flex items-center gap-2">
                @if ($status === 'not_clocked_in')
                    <span class="px-3 py-1 text-xs font-medium bg-slate-100 text-neutral-950 border border-slate-200">
                        Off Duty
                    </span>
                @elseif ($status === 'working')
                    <span class="px-3 py-1 text-xs font-medium bg-emerald-100 text-emerald-700 border border-emerald-200">
                        On Duty
                    </span>
                @elseif ($status === 'on_break')
                    <span class="px-3 py-1 text-xs font-medium bg-amber-100 text-amber-700 border border-amber-200">
                        On Break
                    </span>
                @endif

                @if ($status === 'working' && isset($attendance->time_in))
                    <!-- Live Shift Counter Object passed into JS payload -->
                    <span id="live-runtime-counter"
                          data-start="{{ \Carbon\Carbon::parse($attendance->time_in)->toIso8601String() }}"
                          class="px-3 py-1 text-xs font-medium bg-slate-800 text-white font-mono tracking-wider">
                        00:00:00
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Attendance Action Tiles Grid -->
    <div class="p-5 pt-4 bg-white">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">

            <!-- TIME IN FORM -->
            <form id="form-clock-in" action="{{ route('attendance.clock-in') }}" method="POST" onsubmit="triggerModal(event, 'Confirm Time In', 'Start your shift?', 'form-clock-in')">
                @csrf
                <button type="submit"
                    {{ $status !== 'not_clocked_in' ? 'disabled' : '' }}
                    class="w-full h-full border border-slate-200 bg-white hover:bg-indigo-50 text-left p-4 disabled:bg-slate-100 disabled:text-neutral-400 transition rounded shadow-sm group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-neutral-500 font-semibold">Time In</p>
                            <p class="mt-2 text-xl font-bold text-neutral-950">
                                {{ !empty($attendance->time_in) ? \Carbon\Carbon::parse($attendance->time_in)->format('g:i A') : '--:--' }}
                            </p>
                        </div>
                        <i class="bi bi-play-circle text-blue-600 text-xl group-hover:scale-110 transition-transform"></i>
                    </div>
                </button>
            </form>

            <!-- BREAK OUT FORM -->
            <form id="form-break-out" action="{{ route('attendance.break-out') }}" method="POST" onsubmit="triggerModal(event, 'Confirm Break Out', 'Go on break?', 'form-break-out')">
                @csrf
                <button type="submit"
                    {{ ($status !== 'working' || !empty($attendance->break_out)) ? 'disabled' : '' }}
                    class="w-full h-full border border-slate-200 bg-white hover:bg-amber-50 text-left p-4 disabled:bg-slate-100 disabled:text-neutral-400 transition rounded shadow-sm group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-neutral-500 font-semibold">Break Out</p>
                            <p class="mt-2 text-xl font-bold text-neutral-950">
                                {{ !empty($attendance->break_out) ? \Carbon\Carbon::parse($attendance->break_out)->format('g:i A') : '--:--' }}
                            </p>
                        </div>
                        <i class="bi bi-cup-hot text-amber-600 text-xl group-hover:scale-110 transition-transform"></i>
                    </div>
                </button>
            </form>

            <!-- BREAK IN FORM -->
            <form id="form-break-in" action="{{ route('attendance.break-in') }}" method="POST" onsubmit="triggerModal(event, 'Confirm Break In', 'Resume work?', 'form-break-in')">
                @csrf
                <button type="submit"
                    {{ $status !== 'on_break' ? 'disabled' : '' }}
                    class="w-full h-full border border-slate-200 bg-white hover:bg-emerald-50 text-left p-4 disabled:bg-slate-100 disabled:text-neutral-400 transition rounded shadow-sm group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-neutral-500 font-semibold">Break In</p>
                            <p class="mt-2 text-xl font-bold text-neutral-950">
                                {{ !empty($attendance->break_in) ? \Carbon\Carbon::parse($attendance->break_in)->format('g:i A') : '--:--' }}
                            </p>
                        </div>
                        <i class="bi bi-arrow-repeat text-emerald-600 text-xl group-hover:scale-110 transition-transform"></i>
                    </div>
                </button>
            </form>

            <!-- TIME OUT FORM -->
            <form id="form-clock-out" action="{{ route('attendance.clock-out') }}" method="POST" onsubmit="triggerModal(event, 'Confirm Time Out', 'End your shift?', 'form-clock-out')">
                @csrf
                <button type="submit"
                    {{ $status !== 'working' ? 'disabled' : '' }}
                    class="w-full h-full border border-slate-200 bg-white hover:bg-rose-50 text-left p-4 disabled:bg-slate-100 disabled:text-neutral-400 transition rounded shadow-sm group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-neutral-500 font-semibold">Time Out</p>
                            <p class="mt-2 text-xl font-bold text-neutral-950">
                                {{ !empty($attendance->time_out) ? \Carbon\Carbon::parse($attendance->time_out)->format('g:i A') : '--:--' }}
                            </p>
                        </div>
                        <i class="bi bi-box-arrow-right text-red-600 text-xl group-hover:scale-110 transition-transform"></i>
                    </div>
                </button>
            </form>

            <!-- TOTAL WORKED HOURS CONTAINER -->
            <div class="border border-slate-200 bg-slate-50 p-4 rounded shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-neutral-500 font-semibold">Total Hours</p>
                        <p class="mt-2 text-xl font-black text-neutral-950">
                            {{ number_format(($attendance->total_hours ?? 0.00), 2) }} <span class="text-xs font-normal text-gray-500">hrs</span>
                        </p>
                    </div>
                    <i class="bi bi-calculator text-purple-600 text-xl"></i>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
  // Global tracking state for the custom confirmation modal window
  let activeTargetFormId = null;

  /**
   * 1. Custom Modal Interceptor Engine
   */
  function triggerModal(event, title, message, formId) {
      event.preventDefault();
      activeTargetFormId = formId;

      const titleEl = document.getElementById('modal-title');
      const descEl = document.getElementById('modal-description');

      if (titleEl) titleEl.innerText = title;
      if (descEl) descEl.innerText = message;

      const confirmBtn = document.getElementById('modal-confirm-btn');
      if (confirmBtn) {
          confirmBtn.onclick = function() {
              if (activeTargetFormId) {
                  document.getElementById(activeTargetFormId).submit();
              }
          };
      }

      const modalEl = document.getElementById('custom-confirm-modal');
      if (modalEl) modalEl.classList.remove('hidden');
  }

  /**
   * Closes the confirmation modal
   */
  function closeConfirmationModal() {
      const modalEl = document.getElementById('custom-confirm-modal');
      if (modalEl) modalEl.classList.add('hidden');
      activeTargetFormId = null;
  }

  /**
   * 2. Real-Time Dynamic Live Hours Runtime Clock
   */
  (function() {
      // Safely pull string formats using Laravel's Carbon component values
      const timeInValue = "{{ !empty($attendance->time_in) ? \Carbon\Carbon::parse($attendance->time_in)->toIso8601String() : '' }}";
      const statusValue = "{{ $status }}";

      if (statusValue !== 'working' || !timeInValue) {
          return;
      }

      const timeInEpoch = new Date(timeInValue).getTime();
      const breakOutTime = "{{ !empty($attendance->break_out) ? \Carbon\Carbon::parse($attendance->break_out)->toIso8601String() : '' }}";
      const breakInTime = "{{ !empty($attendance->break_in) ? \Carbon\Carbon::parse($attendance->break_in)->toIso8601String() : '' }}";

      let preCalculatedBreakDeductions = 0;

      if (breakOutTime && breakInTime) {
          const startBreak = new Date(breakOutTime).getTime();
          const endBreak = new Date(breakInTime).getTime();
          preCalculatedBreakDeductions = endBreak - startBreak;
      }

      function updateLiveRuntimeTrackerClock() {
          const nowEpoch = new Date().getTime();
          let elapsedMs = nowEpoch - timeInEpoch;
          elapsedMs -= preCalculatedBreakDeductions;

          if (elapsedMs < 0) {
              elapsedMs = 0;
          }

          const hours = Math.floor(elapsedMs / 3600000);
          const minutes = Math.floor((elapsedMs % 3600000) / 60000);
          const seconds = Math.floor((elapsedMs % 60000) / 1000);

          const pad = (num) => String(num).padStart(2, '0');

          const counterEl = document.getElementById('live-runtime-counter');
          if (counterEl) {
              counterEl.innerText = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
          }
      }

      updateLiveRuntimeTrackerClock();
      setInterval(updateLiveRuntimeTrackerClock, 1000);
  })();

  /**
   * 3. Real-Time Digital Clock for Header
   */
  (function() {
      function updateWidgetClock() {
          const now = new Date();
          let hours = now.getHours();
          const minutes = String(now.getMinutes()).padStart(2, '0');
          const seconds = String(now.getSeconds()).padStart(2, '0');
          const ampm = hours >= 12 ? 'PM' : 'AM';

          hours = hours % 12;
          hours = hours ? hours : 12; // The hour '0' should be '12'
          hours = String(hours).padStart(2, '0');

          const clockEl = document.getElementById('widget-digital-clock');
          if (clockEl) {
              clockEl.innerText = `${hours}:${minutes}:${seconds} ${ampm}`;
          }
      }

      updateWidgetClock();
      setInterval(updateWidgetClock, 1000);
  })();
</script>

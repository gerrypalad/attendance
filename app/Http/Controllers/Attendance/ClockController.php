<?php

namespace App\Http\Controllers\Attendance;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Attendance\Attendance;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ClockController extends Controller
{
    /**
     * Employee Clock In (Updates Pre-Generated Daily Record)
     * Route: POST /attendance/clock-in
     */
    public function clockIn(Request $request): RedirectResponse
    {
        $userId = Auth::id();
        $today  = Carbon::today()->toDateString();
        $now    = Carbon::now();

        // Locate the pre-generated record for today using our converted scope
        $attendance = Attendance::forToday($userId, $today)->first();

        // 1. Guard Clause: If the row already has a clock-in time, reject the attempt
        if ($attendance && !empty($attendance->time_in)) {
            return back()->with('error', 'You have already timed in today.');
        }

        // 2. Main Logic: Since records are generated daily, update the existing row
        if ($attendance) {
            $attendance->update([
                'time_in' => $now
            ]);

            return back()->with('success', 'Time in successful.');
        }

        // Backup fallback: Create row if daily generation script failed to run
        Attendance::create([
            'user_id'   => $userId,
            'work_date' => $today,
            'time_in'   => $now
        ]);

        return back()->with('success', 'Time in recorded (New row initialized).');
    }

    /**
     * Employee Break Out
     * Route: POST /attendance/break-out
     */
    public function breakOut(): RedirectResponse
    {
        $userId = Auth::id();
        $today  = Carbon::today()->toDateString();
        $now    = Carbon::now();

        $attendance = Attendance::forToday($userId, $today)->first();

        if (!$attendance || empty($attendance->time_in)) {
            return back()->with('error', 'You must time in first.');
        }

        if (!empty($attendance->break_out)) {
            return back()->with('error', 'Break out already recorded.');
        }

        $attendance->update([
            'break_out' => $now
        ]);

        return back()->with('success', 'Break out recorded.');
    }

    /**
     * Employee Break In
     * Route: POST /attendance/break-in
     */
    public function breakIn(): RedirectResponse
    {
        $userId = Auth::id();
        $today  = Carbon::today()->toDateString();
        $now    = Carbon::now();

        $attendance = Attendance::forToday($userId, $today)->first();

        if (!$attendance) {
            return back()->with('error', 'Attendance record not found.');
        }

        if (empty($attendance->break_out)) {
            return back()->with('error', 'You must break out first.');
        }

        if (!empty($attendance->break_in)) {
            return back()->with('error', 'Break in already recorded.');
        }

        $attendance->update([
            'break_in' => $now
        ]);

        return back()->with('success', 'Break in recorded.');
    }

    /**
     * Employee Clock Out
     * Route: POST /attendance/clock-out
     */
    public function clockOut(): RedirectResponse
    {
        $userId = Auth::id();
        $today  = Carbon::today()->toDateString();
        $now    = Carbon::now();

        $attendance = Attendance::forToday($userId, $today)->first();

        if (!$attendance || empty($attendance->time_in)) {
            return back()->with('error', 'You must time in first.');
        }

        // CRITICAL GUARD: Block clock out if they are actively sitting on an open break
        if (!empty($attendance->break_out) && empty($attendance->break_in)) {
            return back()->with('error', 'You must resume from break before timing out.');
        }

        if (!empty($attendance->time_out)) {
            return back()->with('error', 'You have already timed out.');
        }

        // Calculate hours safely using our Attendance model utility method
        $totalHours = $attendance->calculateTotalHours(
            $attendance->time_in,
            $now,
            $attendance->break_out,
            $attendance->break_in
        );

        $attendance->update([
            'time_out'    => $now,
            'total_hours' => $totalHours
        ]);

        return back()->with('success', 'Time out successful.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $today  = Carbon::today()->toDateString();

        // 1. Fetch today's record (returns null if they haven't clocked in yet)
        $attendance = Attendance::forToday($userId, $today)->first();

        // 2. Compute the status string for your UI badges and buttons
        $status = 'not_clocked_in';

        if ($attendance) {
            if (!empty($attendance->time_in) && empty($attendance->time_out)) {
                $status = 'working';

                // If they have clicked break out but haven't returned yet
                if (!empty($attendance->break_out) && empty($attendance->break_in)) {
                    $status = 'on_break';
                }
            }
        }

        // 3. Pass both variables down to your view layout
        return view('dashboard', compact('attendance', 'status'));
    }
}

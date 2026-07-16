<?php

namespace App\Http\Controllers\Attendance;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;

class AttendancePageController extends Controller
{
    /**
     * Employee Timeclock Dashboard
     * Route: GET /attendance/timeclock
     */
    public function timeclock()
    {
        $userId = Auth::id();
        $today  = Carbon::today()->toDateString();

        $attendance = Attendance::forToday($userId, $today)->first();
        $status     = $this->resolveStatus($attendance);

        return view('attendance.timeclock', compact('attendance', 'status', 'today'));
    }

    /**
     * Employee Attendance Records Ledger Logs
     * Route: GET /attendance/records
     */
    public function records(Request $request)
    {
        $isAdmin = Auth::user()->is_admin;

        $filters = [
            'date_from' => $request->query('date_from'),
            'date_to'   => $request->query('date_to'),
            'user_id'   => $isAdmin ? $request->query('user_id') : Auth::id(),
        ];

        $records = Attendance::with('user')
            ->applyFilters(array_filter($filters))
            ->orderBy('work_date', 'DESC')
            ->paginate(20)
            ->withQueryString();

        // In your records() method
        $excludedUserIds = [4, 6]; // Add the IDs you want to exclude

        $users = $isAdmin
            ? User::orderBy('name', 'ASC')
                ->whereNotIn('id', $excludedUserIds)
                ->get()
            : [];

        return view('attendance.records', compact('records', 'filters', 'users', 'isAdmin'));
    }

    /**
     * Edit Attendance Record Form
     * Route: GET /attendance/edit/{id}
     */
    public function edit(int $id)
    {
        $attendance = Attendance::with('user')->find($id);

        if (!$attendance) {
            abort(404, 'Record not found');
        }

        if (!Auth::user()->is_admin && $attendance->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('attendance.edit', compact('attendance'));
    }

    /**
     * Update Attendance Record Action
     * Route: PUT /attendance/update/{id}
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $attendance = Attendance::find($id);

        if (!$attendance) {
            abort(404, 'Record not found');
        }

        if (!Auth::user()->is_admin && $attendance->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $mergeDateTime = function(?string $timeInput) use ($attendance): ?string {
            if (empty($timeInput)) {
                return null;
            }
            $cleanTime = Carbon::parse($timeInput)->format('H:i');
            return $attendance->work_date->format('Y-m-d') . ' ' . $cleanTime . ':00';
        };

        $data = [
            'time_in'   => $mergeDateTime($request->input('time_in')),
            'break_out' => $mergeDateTime($request->input('break_out')),
            'break_in'  => $mergeDateTime($request->input('break_in')),
            'time_out'  => $mergeDateTime($request->input('time_out')),
            'remarks'   => $request->input('remarks'),
        ];

        if (!empty($data['time_in']) && !empty($data['time_out'])) {
            $data['total_hours'] = $attendance->calculateTotalHours(
                $data['time_in'], $data['time_out'], $data['break_out'], $data['break_in']
            );
        } else {
            $data['total_hours'] = 0.00;
        }

        $attendance->update($data);

        return redirect()->route('attendance.records')->with('success', 'Attendance tracking hours updated successfully.');
    }

    /**
     * Delete Attendance Record
     * Route: DELETE /attendance/delete/{id}
     */
    public function delete(int $id): RedirectResponse
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Admin access required');
        }

        $attendance = Attendance::find($id);
        if (!$attendance) {
            abort(404, 'Record not found');
        }

        $attendance->delete();

        return redirect()->route('attendance.records')->with('success', 'Attendance record deleted successfully.');
    }

    /**
     * Export Filtered Data into PDF via Dompdf
     * Route: GET /attendance/export-pdf
     */
    public function exportPdf(Request $request)
    {
        $isAdmin = Auth::user()->is_admin;

        $filters = [
            'date_from' => $request->query('date_from'),
            'date_to'   => $request->query('date_to'),
            'user_id'   => $isAdmin ? $request->query('user_id') : Auth::id(),
        ];

        $filters = array_filter($filters);
        $records = Attendance::with('user')->applyFilters($filters)->orderBy('work_date', 'ASC')->get();

        $logoPath = public_path('assets/images/zzenitram-logo600.png');
        $logoData = '';

        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = 'data:image/' . $logoType . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        $html = view('attendance.pdf_template', compact('records', 'filters', 'logoData'))->render();

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream("attendance_report_" . now()->format('Ymd_His') . ".pdf", ["Attachment" => true]);
    }

    /**
     * Display the Live Attendance Monitoring Board
     * Route: GET /attendance/monitor
     */
    public function monitor()
    {
        // if (!Auth::user()->is_admin) {
        //     abort(403);
        // }
        return view('attendance.monitor', ['title' => 'Live Attendance Monitor', 'today' => Carbon::today()->toDateString()]);
    }

    /**
     * API Endpoint: Returns JSON data for the monitoring board auto-refresh
     * Route: GET /attendance/monitor-data
     */
    public function monitorData(): JsonResponse
    {
        // Set to empty [] to show everyone, or add specific IDs to exclude them (e.g., [4, 6, 9])
        $excludedUserIds = [4, 6, 12];
        $today = Carbon::today()->toDateString();

        // Only apply whereNotIn if there are IDs to exclude, preventing empty array query execution bugs
        $usersQuery = User::with(['attendances' => function ($query) use ($today) {
            $query->whereDate('work_date', $today);
        }])->orderBy('name', 'ASC');

        if (!empty($excludedUserIds)) {
            $usersQuery->whereNotIn('id', $excludedUserIds);
        }

        $users = $usersQuery->get();

        $formattedData = $users->map(function ($user) {
            $attendance = $user->attendances->first();
            $status = 'not_clocked_in';

            if ($attendance && !empty($attendance->time_in)) {
                if (!empty($attendance->time_out)) {
                    $status = 'shift_end';
                } elseif (!empty($attendance->break_out) && empty($attendance->break_in)) {
                    $status = 'on_break';
                } else {
                    $status = 'working';
                }
            }

            return [
                'username'       => $user->name,
                'role'           => $user->is_admin ? 'Administrator' : 'Employee',
                'time_in'        => $attendance && $attendance->time_in ? Carbon::parse($attendance->time_in)->format('g:i A') : '--:--',
                'break_out'      => $attendance && $attendance->break_out ? Carbon::parse($attendance->break_out)->format('g:i A') : '--:--',
                'break_in'       => $attendance && $attendance->break_in ? Carbon::parse($attendance->break_in)->format('g:i A') : '--:--',
                'time_out'       => $attendance && $attendance->time_out ? Carbon::parse($attendance->time_out)->format('g:i A') : '--:--',
                'total_hours'    => $attendance ? number_format((float)$attendance->total_hours, 2) : '0.00',
                'status'         => $status,
                'raw_time_in'    => $attendance && $attendance->time_in ? Carbon::parse($attendance->time_in)->toIso8601String() : null,
                'raw_break_out'  => $attendance && $attendance->break_out ? Carbon::parse($attendance->break_out)->toIso8601String() : null,
                'raw_break_in'   => $attendance && $attendance->break_in ? Carbon::parse($attendance->break_in)->toIso8601String() : null,
                'raw_time_out'   => $attendance && $attendance->time_out ? Carbon::parse($attendance->time_out)->toIso8601String() : null,
            ];
        });

        return response()->json([
            'date'        => Carbon::today()->format('l, d F Y'),
            'timestamp'   => Carbon::now()->format('h:i:s A'),
            'data'        => $formattedData,
            'total_users' => $formattedData->count()
        ]);
    }


    /**
     * Resolves the user's shift status based on database values
     */
    private function resolveStatus(?Attendance $attendance): string
    {
        if (!$attendance || empty($attendance->time_in)) return 'not_clocked_in';
        if (!empty($attendance->time_out)) return 'ended';
        if (!empty($attendance->break_out) && empty($attendance->break_in)) return 'on_break';
        return 'working';
    }
}

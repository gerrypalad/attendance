<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #1e293b; line-height: 1.5; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .logo-box { width: 50%; vertical-align: top; }
        .title-box { width: 50%; text-align: right; vertical-align: top; }
        .title-box h2 { margin: 0; color: #0f172a; font-size: 22px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        .title-box p { margin: 4px 0 0 0; color: #64748b; font-size: 11px; }

        /* Cut-off date styling */
        .cutoff-info {
            margin-top: 10px;
            padding: 8px 12px;
            background: #f1f5f9;
            border-left: 3px solid #2563eb;
            font-size: 10px;
            color: #475569;
        }
        .cutoff-info .label {
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 9px;
            margin-bottom: 2px;
        }
        .cutoff-info .dates {
            font-size: 11px;
            font-weight: 600;
            color: #1e293b;
        }

        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th { background: #f8fafc; color: #475569; font-weight: bold; padding: 8px 10px; border: 1px solid #cbd5e1; text-align: left; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px; }
        .data-table td { padding: 8px 10px; border: 1px solid #e2e8f0; color: #334155; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-purple { color: #6d28d9; font-weight: bold; }

        /* Grand Total Row Styling */
        .data-table tfoot td {
            background: #0f172a;
            color: #ffffff;
            font-weight: bold;
            padding: 10px;
            border: 1px solid #0f172a;
            font-size: 12px;
        }
        .data-table tfoot .total-label {
            text-align: right;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .data-table tfoot .total-value {
            text-align: right;
            color: #fbbf24;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="logo-box">
                @if($logoData) <img src="{{ $logoData }}" style="max-height: 45px;"> @endif

                @php
                    $dateFrom = !empty($filters['date_from'])
                        ? \Carbon\Carbon::parse($filters['date_from'])->format('M d, Y')
                        : 'Beginning';
                    $dateTo = !empty($filters['date_to'])
                        ? \Carbon\Carbon::parse($filters['date_to'])->format('M d, Y')
                        : 'Present';
                @endphp

                <div class="cutoff-info">
                    <div class="label">Cut-Off Period</div>
                    <div class="dates">{{ $dateFrom }} to {{ $dateTo }}</div>
                </div>
            </td>
            <td class="title-box">
                <h2>Attendance Report</h2>
                <p>Date Generated: {{ now()->format('M d, Y h:i A') }}</p>
            </td>
        </tr>
    </table>

    @php
        $grandTotal = $records->sum(fn($r) => (float) ($r->total_hours ?? 0));
    @endphp

    <table class="data-table">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Work Date</th>
                <th>Clock In</th>
                <th>Break Out</th>
                <th>Break In</th>
                <th>Clock Out</th>
                <th class="text-right">Hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td class="font-bold">{{ $record->user->name }}</td>
                <td>{{ $record->work_date->format('M j, Y (D)') }}</td>
                <td>{{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('g:i A') : '--' }}</td>
                <td>{{ $record->break_out ? \Carbon\Carbon::parse($record->break_out)->format('g:i A') : '--' }}</td>
                <td>{{ $record->break_in ? \Carbon\Carbon::parse($record->break_in)->format('g:i A') : '--' }}</td>
                <td>{{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('g:i A') : '--' }}</td>
                <td class="text-right text-purple">{{ number_format((float) $record->total_hours, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="total-label">Grand Total</td>
                <td class="total-value">{{ number_format($grandTotal, 2) }} hrs</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>

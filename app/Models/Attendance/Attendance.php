<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $table = 'attendance';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'work_date',
        'time_in',
        'time_out',
        'break_out',
        'break_in',
        'total_hours',
        'remarks',
    ];

    protected $casts = [
        'work_date'   => 'date',
        'time_in'     => 'datetime',
        'time_out'     => 'datetime',
        'break_out'   => 'datetime',
        'break_in'    => 'datetime',
        'total_hours' => 'float',
    ];

    /**
     * Relationship: An attendance record belongs to a User.
     * Replaces CodeIgniter's manual getUser() queries.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * Compute exact decimal hours worked, deducting break time if present.
     */
    public function calculateTotalHours($timeIn, $timeOut, $breakOut = null, $breakIn = null): float
    {
        $start = Carbon::parse($timeIn);
        $end   = Carbon::parse($timeOut);
        $totalSeconds = $end->diffInSeconds($start, true);

        if (!empty($breakOut) && !empty($breakIn)) {
            $breakStart = Carbon::parse($breakOut);
            $breakEnd   = Carbon::parse($breakIn);
            $breakSeconds = $breakEnd->diffInSeconds($breakStart, true);

            if ($breakSeconds > 0) {
                $totalSeconds -= $breakSeconds;
            }
        }

        return $totalSeconds < 0 ? 0.0 : round($totalSeconds / 3600, 2);
    }

    /**
     * Log a "Time In" for a user by updating pre-existing cron rows.
     */
    public function clockIn(int $userId, string $date, string $time): bool
    {
        return $this->where('user_id', $userId)
                    ->whereDate('work_date', $date)
                    ->update(['time_in' => $time]); // Laravel handles updated_at automatically
    }

    /**
     * Local Scope: Build shared query constraints dynamically for application filters.
     * Replaces CI4's protected applyFilters method.
     */
    public function scopeApplyFilters(Builder $query, array $filters): Builder
    {
        if (!empty($filters['date_from'])) {
            $query->where('work_date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->where('work_date', '<=', $filters['date_to']);
        }
        if (!empty($filters['user_id'])) {
            $query->where('user_id', (int) $filters['user_id']);
        }
        return $query;
    }

    /**
     * Fetch records with dynamic application filters and automated pagination.
     * Replaces CI4's manual select, join, offset, and getAll methods.
     */
    public function getAll(array $filters = [], int $limit = 10)
    {
        return $this->with('user') // Eager loads user rows to prevent N+1 memory issues
                    ->applyFilters($filters)
                    ->orderBy('work_date', 'DESC')
                    ->whereHas('user', function ($query) {
                        $query->orderBy('name', 'ASC'); // Assuming Breeze 'name' instead of CI4 'username'
                    })
                    ->paginate($limit); // Automatic pagination matching Bootstrap/Tailwind layout limits
    }

    /**
     * Count total attendance records based on current active filters.
     */
    public function countAll(array $filters = []): int
    {
        return $this->applyFilters($filters)->count();
    }

    /**
     * Get all filtered attendance records without pagination limits for reporting tools.
     */
    public function getAllForExport(array $filters = [])
    {
        return $this->with('user')
                    ->applyFilters($filters)
                    ->orderBy('work_date', 'ASC')
                    ->get();
    }

    /**
     * Find an attendance record by its ID with the user object eager loaded.
     */
    public function findWithUser(int $id): ?self
    {
        return $this->with('user')->find($id);
    }

    /**
     * Local Query Scope: Find a user's attendance record for a specific date.
     * Note: The "scope" prefix must be lowercase, followed by CamelCase.
     */
    public function scopeForToday(Builder $query, int $userId, string $date): Builder
    {
        return $query->where('user_id', $userId)
                     ->whereDate('work_date', $date);
    }
}

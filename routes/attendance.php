<?php

use App\Http\Controllers\Attendance\ClockController;
use App\Http\Controllers\Attendance\AttendancePageController;

Route::middleware('auth')->prefix('attendance')->name('attendance.')->group(function () {
    Route::post('clock-in', [ClockController::class, 'clockIn'])->name('clock-in');
    Route::post('break-out', [ClockController::class, 'breakOut'])->name('break-out');
    Route::post('break-in', [ClockController::class, 'breakIn'])->name('break-in');
    Route::post('clock-out', [ClockController::class, 'clockOut'])->name('clock-out');
});



Route::middleware('auth')->prefix('attendance')->name('attendance.')->group(function () {
    Route::get('timeclock', [AttendancePageController::class, 'timeclock'])->name('timeclock');
    Route::get('records', [AttendancePageController::class, 'records'])->name('records');
    Route::get('edit/{id}', [AttendancePageController::class, 'edit'])->name('edit');
    Route::put('update/{id}', [AttendancePageController::class, 'update'])->name('update');
    Route::delete('delete/{id}', [AttendancePageController::class, 'delete'])->name('delete');
    Route::get('export-pdf', [AttendancePageController::class, 'exportPdf'])->name('export-pdf');
    Route::get('monitor', [AttendancePageController::class, 'monitor'])->name('monitor');
    Route::get('monitor-data', [AttendancePageController::class, 'monitorData'])->name('monitor-data');
});

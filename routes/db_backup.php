<?php

use App\Http\Controllers\BackupController;

Route::middleware(['auth', 'admin'])->prefix('admin/backups')->name('backups.')->group(function () {
    Route::get('/', [BackupController::class, 'index'])->name('index');
    Route::post('/', [BackupController::class, 'store'])->name('store');
    Route::get('/{filename}/download', [BackupController::class, 'download'])
        ->where('filename', '[a-zA-Z0-9_\-\.]+')
        ->name('download');
    Route::delete('/{filename}', [BackupController::class, 'destroy'])
        ->where('filename', '[a-zA-Z0-9_\-\.]+')
        ->name('destroy');
});

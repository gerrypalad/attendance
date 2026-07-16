<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id(); // Primary Key auto-increment

            // Foreign Key linking to the users table. Deletes attendance if a user account is removed.
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->date('work_date');

            // Attendance timestamps (nullable since a cron job might pre-create rows)
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->time('break_out')->nullable();
            $table->time('break_in')->nullable();

            // Total hours stored as decimal (e.g., 8.50 hours). Max 5 digits total, 2 decimal places.
            $table->decimal('total_hours', 5, 2)->default(0.00);

            $table->text('remarks')->nullable();

            // Generates created_at and updated_at columns automatically
            $table->timestamps();

            // Indexes added for faster filtering performance in reporting tools
            $table->index(['user_id', 'work_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};

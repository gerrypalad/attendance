<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('io_outgoing_docs', function (Blueprint $table) {
            $table->fullText(['documents', 'remarks_action']);
        });
    }
    public function down(): void {
        Schema::table('io_outgoing_docs', function (Blueprint $table) {
            $table->dropFullText(['documents', 'remarks_action']);
        });
    }
};

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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Who did it?
            $table->foreignId('user_id')->nullable()->index()->constrained()->onDelete('set null');

            // What happened?
            $table->string('action', 50)->index(); // 'view_prediction', 'predict', 'like', 'scroll'

            // On what?
            $table->nullableMorphs('subject'); // subject_id + subject_type (prediction, comment, tag, user)

            // Extra context (flexible!)
            $table->json('metadata')->nullable();

            // Session & device
            $table->string('session_id', 60)->nullable()->index();
            $table->string('device_type', 20)->nullable(); // mobile, desktop
            $table->string('platform', 20)->nullable();   // ios, android, web

            // When?
            $table->timestamp('created_at')->useCurrent()->index();

            // Partitioning-ready
            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

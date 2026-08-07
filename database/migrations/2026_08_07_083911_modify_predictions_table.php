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
        Schema::table('predictions', function (Blueprint $table) {
            $table->string('title', 1024)->nullable()->change();
            $table->string('language', 20)->nullable()->after('topic_id');
            $table->string('region', 50)->nullable()->after('language');
            $table->string('title_hash', 64)->nullable()->unique()->after('region');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('predictions', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
            $table->dropUnique(['title_hash']);
            $table->dropColumn(['language', 'region', 'title_hash']);
        });
    }
};

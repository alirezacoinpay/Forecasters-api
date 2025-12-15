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
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile')->change()->nullable();
            $table->string('username')->change()->nullable();
            $table->string('password')->change()->nullable();
            $table->string('ip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile')->change()->nullable(false);
            $table->string('username')->change()->nullable(false);
            $table->string('password')->change()->nullable(false);
            $table->dropColumn('ip');
        });
    }
};

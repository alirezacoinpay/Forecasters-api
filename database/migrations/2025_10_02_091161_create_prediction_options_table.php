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
        Schema::create('prediction_options', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('is_true')->nullable();
            $table->unsignedBigInteger('prediction_id');
            $table->foreign('prediction_id')->references('id')->on('predictions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prediction_options');
    }
};

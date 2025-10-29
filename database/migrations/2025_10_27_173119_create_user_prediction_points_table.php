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
        Schema::create('user_prediction_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_prediction_id');
            $table->foreign('user_prediction_id')
                ->references('id')
                ->on('user_predictions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->integer('points');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_prediction_points');
    }
};

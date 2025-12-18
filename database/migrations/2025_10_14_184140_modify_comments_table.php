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
        Schema::table('comments', function (Blueprint $table) {

            $table->unsignedBigInteger('prediction_id');
            $table->foreign('prediction_id')->references('id')->on('predictions')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {

            $table->dropForeign('comments_prediction_id_foreign');
            $table->dropColumn('prediction_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {

            $table->unsignedBigInteger('comment_id');
            $table->foreign('comment_id')->references('id')->on('comments')->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {

            $table->dropColumn('comment_id');
            $table->dropForeign('comments_comment_id_foreign');

        });
    }
};

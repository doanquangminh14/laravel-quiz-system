<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();

            // Khóa ngoại tới quizzes
            $table->unsignedBigInteger('quiz_id');
            $table->foreign('quiz_id')
                  ->references('id')
                  ->on('quizzes')
                  ->onDelete('cascade');

            // Khóa ngoại tới users
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->integer('score')->nullable(); // điểm số
            $table->integer('time_spent')->nullable(); // thời gian làm bài (giây)
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

             $table->integer('correct_answers')->default(0);
             $table->integer('total_questions')->default(0);
        });
    }

    public function down(): void
    {
        //Schema::dropIfExists('records');
           Schema::table('records', function (Blueprint $table) {
        $table->dropColumn(['correct_answers', 'total_questions']);
    });
    }
};

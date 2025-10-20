<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mcqs', function (Blueprint $table) {
            $table->id();
            $table->text('question');    // câu hỏi
            $table->string('a')->nullable();
            $table->string('b')->nullable();
            $table->string('c')->nullable();
            $table->string('d')->nullable();
            $table->string('correct_ans'); // đáp án đúng (a, b, c, d)

            // Khóa ngoại tới admin
            $table->unsignedBigInteger('admin_id');
            $table->foreign('admin_id')
                  ->references('id')
                  ->on('admin')
                  ->onDelete('cascade');

            // Khóa ngoại tới quizzes
            $table->unsignedBigInteger('quiz_id');
            $table->foreign('quiz_id')
                  ->references('id')
                  ->on('quizzes')
                  ->onDelete('cascade');

            // Khóa ngoại tới categories
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mcqs');
    }
};

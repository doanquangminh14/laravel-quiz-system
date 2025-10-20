<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
Schema::create('mcq_records', function (Blueprint $table) {
    $table->id();

    // Liên kết tới user
    $table->unsignedBigInteger('user_id');
    $table->foreign('user_id')
          ->references('id')
          ->on('users')
          ->onDelete('cascade');

    // Liên kết tới record
    $table->unsignedBigInteger('record_id');
    $table->foreign('record_id')
          ->references('id')
          ->on('records')
          ->onDelete('cascade');

    // Liên kết tới mcq
    $table->unsignedBigInteger('mcq_id');
    $table->foreign('mcq_id')
          ->references('id')
          ->on('mcqs')
          ->onDelete('cascade');

    $table->string('select_answer')->nullable();
    $table->boolean('is_correct')->default(false);

    $table->timestamps();
});


    }

    public function down(): void
    {
        Schema::dropIfExists('mcq_records');
    }
};

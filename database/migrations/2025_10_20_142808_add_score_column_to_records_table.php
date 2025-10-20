<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations (Thêm các cột mới vào bảng đã tồn tại).
     */
    public function up(): void
    {
        // Sử dụng Schema::table() để sửa đổi bảng đã tồn tại
        Schema::table('users', function (Blueprint $table) {
            
            // 1. URL ảnh đại diện (avatar)
            // Đặt cột này sau cột 'email' (hoặc cột nào đó đã có)
            $table->string('avatar')
                  ->nullable()
                  ->default('images/default-avatar.png')
                  ->after('email'); 
            
            // 2. Tổng điểm (integer) - dùng để xếp hạng nhanh
            $table->unsignedInteger('total_score')->default(0); 
            
            // 3. Tổng số Quiz đã hoàn thành
            $table->unsignedSmallInteger('total_quizzes_completed')->default(0); 
            
            // 4. Chuỗi thắng liên tiếp (Streak)
            $table->unsignedSmallInteger('current_streak')->default(0);
        });
    }

    /**
     * Reverse the migrations (Xóa các cột đã thêm).
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Xóa các cột đã thêm khi rollback
            $table->dropColumn([
                'avatar', 
                'total_score', 
                'total_quizzes_completed', 
                'current_streak'
            ]);
        });
    }
};
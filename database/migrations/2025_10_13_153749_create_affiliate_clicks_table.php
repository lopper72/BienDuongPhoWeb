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
        Schema::create('affiliate_clicks', function (Blueprint $table) {
             $table->id();
            
            // Nếu có bảng affiliate_links, bạn có thể dùng foreign key:
            // $table->foreignId('affiliate_link_id')->constrained('affiliate_links')->onDelete('cascade');
            $table->unsignedBigInteger('affiliate_link_id')->nullable()->index();
            
            $table->string('ip', 45)->nullable();        // Lưu địa chỉ IP (IPv4/IPv6)
            $table->text('user_agent')->nullable();      // Trình duyệt / thiết bị
            $table->string('referrer')->nullable();      // Nguồn giới thiệu (Facebook, Google, ...)
            $table->timestamp('clicked_at')->useCurrent(); // Thời điểm click
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_clicks');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('product_detail', function (Blueprint $table) {
            $table->text('short_description')->nullable()->change(); // Thay đổi kiểu dữ liệu cột và cho phép null
        });
    }

    public function down()
    {
        Schema::table('product_detail', function (Blueprint $table) {
            $table->string('short_description')->nullable()->change(); // Khôi phục kiểu dữ liệu cũ và cho phép null nếu cần
        });
    }
};
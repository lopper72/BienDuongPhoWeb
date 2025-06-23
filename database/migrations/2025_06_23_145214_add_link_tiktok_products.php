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
        Schema::table('products', function (Blueprint $table) {
            $table->string('tiktok_link')->nullable(); // Thêm cột tiktok_link
        });
    }

    // ... existing code ...

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('tiktok_link'); // Xóa cột tiktok_link nếu cần
        });
    }
};

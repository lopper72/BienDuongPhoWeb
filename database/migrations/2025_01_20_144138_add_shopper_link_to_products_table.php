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
            $table->string('shopper_link')->nullable(); // Thêm cột shopper_link
        });
    }

    // ... existing code ...

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('shopper_link'); // Xóa cột shopper_link nếu cần
        });
    }
};

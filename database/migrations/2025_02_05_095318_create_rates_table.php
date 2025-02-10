<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id(); // Tạo cột id tự động tăng
            $table->unsignedBigInteger('user_id'); // Cột user_id
            $table->unsignedBigInteger('product_id'); // Cột product_id
            $table->integer('rate')->default(0); // Cột rate với giá trị mặc định là 0
            $table->timestamps(); // Timestamps cho created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('rates');
    }
};
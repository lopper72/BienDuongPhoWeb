<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::table('product_detail', function (Blueprint $table) {
            $table->integer('number_order')->nullable(); // Thêm cột number_order
        });
    }
    
    public function down() {
        Schema::table('product_detail', function (Blueprint $table) {
            $table->dropColumn('number_order'); // Xóa cột number_order
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category_ids')->nullable();
            $table->string('brand_ids')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_full')->nullable();
            $table->decimal('rate', 8, 2)->nullable();
            $table->string('source')->nullable();
            $table->string('author')->nullable();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['category_ids', 'brand_ids', 'image', 'is_full', 'rate', 'source', 'author']);
        });
    }
};

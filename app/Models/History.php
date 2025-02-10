<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    protected $table = 'history';
    protected $fillable = ['user_id', 'product_id', 'product_detail_id'];

    public function products()
    {
        return $this->hasMany(Product::class, 'id', 'product_id');
    }

    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class, 'id', 'product_detail_id');
    }
}

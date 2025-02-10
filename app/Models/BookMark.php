<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookMark extends Model
{
    use HasFactory;
    protected $table = 'bookmarks';
    protected $fillable = ['user_id', 'product_id', 'product_detail_id'];

    public function products()
    {
        return $this->hasMany(Product::class, 'id', 'product_id');
    }
}

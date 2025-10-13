<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WrapLink extends Model
{
    use HasFactory;
    protected $table = 'wraplinks';
    protected $fillable = ['code', 'name', 'description', 'slug', 'logo'];

//     public function hasProduct()
//     {
//         return $this->hasMany(Product::class, 'brand_id', 'id')->count() > 0;
//     }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateClick extends Model
{
    use HasFactory;
    protected $fillable = [
        'affiliate_link_id',
        'ip',
        'user_agent',
        'referrer',
    ];

    public function link()
    {
        return $this->where('affiliate_link_id', $this->affiliate_link_id)->count();
    }
}

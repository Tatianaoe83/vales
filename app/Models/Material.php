<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'unit', 'price', 'stock', 'description', 'is_active'
    ];

    public function priceHistory()
    {
        return $this->hasMany(MaterialPriceHistory::class)->orderBy('changed_at', 'desc');
    }
}
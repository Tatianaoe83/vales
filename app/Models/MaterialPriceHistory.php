<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialPriceHistory extends Model
{
    use HasFactory;

    protected $fillable = ['material_id', 'price', 'changed_at'];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
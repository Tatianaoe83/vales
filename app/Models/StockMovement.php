<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'user_id',
        'type',
        'quantity',
        'reason',
    ];

    // Relación con el Material
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    // Relación con el Usuario (quién hizo el movimiento)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
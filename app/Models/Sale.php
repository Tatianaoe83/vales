<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sale extends Model
{
    protected $fillable = [
        'folio', 'client_id', 'user_id', 'tipo_venta', 
        'fecha_vencimiento', 'subtotal', 'descuento', 'iva', 'total', 'notas'
    ];

    public function vales()
    {
        return $this->hasMany(Vale::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $year = date('Y');
            $lastSale = Sale::latest()->first();
            $nextId = $lastSale ? $lastSale->id + 1 : 1;
            $consecutivo = str_pad($nextId, 4, '0', STR_PAD_LEFT);
            
            $model->folio = "VTA-{$year}-{$consecutivo}";
        });
    }
}
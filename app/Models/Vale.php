<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Vale extends Model
{
    protected $fillable = [
        'sale_id', 'folio_vale', 'uuid', 'material_id', 
        'cantidad', 'unit_id', 'estatus'
    ];

    
    public function sale() { return $this->belongsTo(Sale::class); }
    public function material() { return $this->belongsTo(Material::class); }
    public function unit() { return $this->belongsTo(Unit::class); }
    public function history() { return $this->hasMany(ValeHistory::class); }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
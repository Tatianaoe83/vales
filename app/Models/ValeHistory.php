<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValeHistory extends Model
{
    use HasFactory;

    // Desactivamos timestamps automáticos para evitar el error de 'updated_at'
    public $timestamps = false; 

    // IMPORTANTE: Esto arregla el error de "diffForHumans"
    // Le dice a Laravel que trate 'created_at' como fecha, no como texto.
    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'vale_id',
        'user_id',
        'estatus_anterior',
        'estatus_nuevo',
        'comentarios'
    ];

    public function vale()
    {
        return $this->belongsTo(Vale::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
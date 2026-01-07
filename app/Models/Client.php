<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rfc',
        'phone',
        'email',
        'address',
        'is_active'
    ];

    public function vales()
    {
        return $this->hasMany(Vale::class); 
    }
}
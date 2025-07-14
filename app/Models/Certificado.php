<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    protected $fillable = [
        'evento',
        'cantidad',
        'responsable',
        'fecha',
        'stock_actual',
        'tipo'
    ];

    protected $casts = [
        'fecha' => 'date',
        'cantidad' => 'integer',
        'stock_actual' => 'integer',
    ];
}

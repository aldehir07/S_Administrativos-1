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
        'stock_actual'
    ];
}

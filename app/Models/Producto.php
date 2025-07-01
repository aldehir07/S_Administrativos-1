<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Clasificacion;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'clasificacion_id',
        'imagen',
        'stock_actual',
        'stock_minimo'
    ];

    public function clasificacion(){
        return $this->belongsTo(Clasificacion::class);
    }
}

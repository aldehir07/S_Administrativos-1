<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{

    protected $fillable = [
        'tipo_movimiento',
        'producto_id',
        'cantidad',
        'fecha',
        'clasificacion_id',
        'evento',
        'lote',
        'fecha_vencimiento',
        'solicitante_id',
        'responsable',
        'motivo',
        'observaciones'
    ];

    public function solicitante(){
        return $this->belongsTo(Solicitante::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}

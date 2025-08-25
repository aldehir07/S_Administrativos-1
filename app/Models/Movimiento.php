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
        'responsable_id',
        'motivo',
        'observaciones',
        'creado_por',
    ];

    public function solicitante(){
        return $this->belongsTo(Solicitante::class);
    }

    public function clasificacion(){
        return $this->belongsTo(Clasificacion::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Relación con Responsable
    public function responsable()
    {
        return $this->belongsTo(Responsable::class);
    }

}

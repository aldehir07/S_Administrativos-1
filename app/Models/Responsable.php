<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsable extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'piso',
        'tipo',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    //Relacion con movimientos
    public function movimientos(){
        return $this->hasMany(Movimiento::class);
    }

    // Scope para responsables activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Scope para responsables manuales
    public function scopeManuales($query)
    {
        return $query->where('tipo', 'manual');
    }

    // Scope para responsables completos
    public function scopeCompletos($query)
    {
        return $query->where('tipo', 'completo');
    }

    // Método para verificar si puede hacer un tipo de movimiento
    public function puedeHacer($tipoMovimiento)
    {
        if ($tipoMovimiento === 'Salida') {
            return true; // Todos pueden hacer salidas
        }

        return $this->tipo === 'completo';
    }
}

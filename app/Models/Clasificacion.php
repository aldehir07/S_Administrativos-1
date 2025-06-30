<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clasificacion extends Model
{

    protected $table = 'clasificaciones'; // <--- Agrega esto

    public function productos(){
        return $this->hasMany(Producto::class);
    }
}

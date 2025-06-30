<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    public function solicitante(){
        return $this->belongsTo(Solicitante::class);
    }
}

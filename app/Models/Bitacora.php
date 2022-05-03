<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;
    
    public static function registrar($accion,$usuario,$texto) {
        //registrar en la bitacora
        $registro = new Bitacora;
        $registro->usuario = $usuario;
        $registro->accion = $accion;
        $registro->texto = $texto;
        $registro->save();
    }
}

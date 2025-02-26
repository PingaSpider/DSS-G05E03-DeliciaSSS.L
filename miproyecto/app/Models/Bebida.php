<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Bebida extends Model
{
    
    protected $primaryKey = 'cod';
    protected $keyType = 'string';
    protected $fillable = ['tamanyo', 'tipoBebida'];

    //get public para obtener la descripcion de la comida
    public function getDescripcionAttribute()
    {
        return fillable['tipoBebida'];
    }
}
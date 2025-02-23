<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Stock extends Model
{
    

    protected $fillable = ['cantidad', 'disponible', 'precio', 'carta_id'];

    public function carta()
    {
        return $this->belongsTo(Carta::class);
    }
}
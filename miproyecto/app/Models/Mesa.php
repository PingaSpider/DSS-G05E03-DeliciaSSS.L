<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Mesa extends Model
{
    protected $table = 'mesas';
    protected $primaryKey = 'codMesa';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'codMesa', 'ocupada', 'cantidadMesa'
    ];

    protected $casts = [
        'ocupada' => 'boolean',
        'cantidadMesa' => 'integer',
    ];

    // Relación con reservas: una mesa puede tener muchas reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'mesa_id', 'codMesa');
    }
}
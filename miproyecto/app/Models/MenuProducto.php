<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MenuProducto extends Model
{
    protected $table = 'menu_producto';
    
    // Esta tabla tiene clave primaria compuesta
    protected $primaryKey = ['menu_cod', 'producto_cod'];
    public $incrementing = false;
    
    protected $fillable = [
        'menu_cod', 'producto_cod', 'cantidad', 'descripcion'
    ];

    protected $casts = [
        'cantidad' => 'integer',
    ];

    // Necesario para tablas pivot con clave primaria compuesta en Laravel
    public function getIncrementing()
    {
        return false;
    }

    protected function setKeysForSaveQuery($query)
    {
        return $query->where('menu_cod', $this->attributes['menu_cod'])
                    ->where('producto_cod', $this->attributes['producto_cod']);
    }
}
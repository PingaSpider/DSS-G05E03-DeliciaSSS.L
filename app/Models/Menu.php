<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'descripcion',
        'producto_id',
        // otros campos necesarios
    ];

    /**
     * Relación con el producto base que representa este menú
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Relación con los productos que componen este menú
     */
    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'menu_producto')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }
}
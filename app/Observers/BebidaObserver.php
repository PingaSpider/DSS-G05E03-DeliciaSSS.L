<?php

namespace App\Observers;

use App\Models\Bebida;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class BebidaObserver
{
    /**
     * Handle the Bebida "creating" event.
     */
    public function creating(Bebida $bebida): void
    {
        // No asignamos producto_id aquí porque necesitamos crear primero el producto
        // y para eso necesitamos que la bebida se guarde primero
    }

    /**
     * Handle the Bebida "created" event.
     */
    public function created(Bebida $bebida): void
    {
        DB::transaction(function () use ($bebida) {
            // Crear el producto base con los datos de la bebida
            $producto = Producto::create([
                'nombre' => $bebida->nombre ?? 'Bebida '.$bebida->id,
                'pvp' => $bebida->pvp ?? 0.00,
                'cod' => 'B-'.$bebida->id, // Prefijo B para bebidas
                'stock' => $bebida->stock ?? 0,
                'disponible' => $bebida->disponible ?? true,
                'precioCompra' => $bebida->precioCompra ?? 0.00,
            ]);
            
            // Actualizar la bebida para vincularla con el producto creado
            $bebida->producto_id = $producto->id;
            // Importante: usar saveQuietly para evitar llamar a creating/created de nuevo
            $bebida->saveQuietly();
        });
    }

    /**
     * Handle the Bebida "updating" event.
     */
    public function updating(Bebida $bebida): void
    {
        // Actualizar el producto cuando se actualice la bebida
        if ($bebida->producto) {
            $bebida->producto->update([
                'nombre' => $bebida->nombre,
                'pvp' => $bebida->pvp ?? $bebida->producto->pvp,
                'stock' => $bebida->stock ?? $bebida->producto->stock,
                'disponible' => $bebida->disponible ?? $bebida->producto->disponible,
                'precioCompra' => $bebida->precioCompra ?? $bebida->producto->precioCompra,
            ]);
        }
    }

    /**
     * Handle the Bebida "deleted" event.
     */
    public function deleted(Bebida $bebida): void
    {
        // Eliminar el producto cuando se elimina la bebida
        if ($bebida->producto) {
            $bebida->producto->delete();
        }
    }
}
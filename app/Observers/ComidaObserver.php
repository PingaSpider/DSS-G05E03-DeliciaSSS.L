<?php

namespace App\Observers;

use App\Models\Comida;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class ComidaObserver
{
    /**
     * Handle the Comida "creating" event.
     */
    public function creating(Comida $comida): void
    {
        // No asignamos producto_id aquí porque necesitamos crear primero el producto
        // y para eso necesitamos que la comida se guarde primero
    }

    /**
     * Handle the Comida "created" event.
     */
    public function created(Comida $comida): void
    {
        DB::transaction(function () use ($comida) {
            // Crear el producto base con los datos de la comida
            $producto = Producto::create([
                'nombre' => $comida->nombre ?? 'Comida '.$comida->id,
                'pvp' => $comida->pvp ?? 0.00,
                'cod' => 'C-'.$comida->id, // Prefijo C para comidas
                'stock' => $comida->stock ?? 0,
                'disponible' => $comida->disponible ?? true,
                'precioCompra' => $comida->precioCompra ?? 0.00,
            ]);
            
            // Actualizar la comida para vincularla con el producto creado
            $comida->producto_id = $producto->id;
            // Importante: usar update para evitar llamar a creating/created de nuevo
            $comida->saveQuietly();
        });
    }

    /**
     * Handle the Comida "updating" event.
     */
    public function updating(Comida $comida): void
    {
        // Actualizar el producto cuando se actualice la comida
        if ($comida->producto) {
            $comida->producto->update([
                'nombre' => $comida->nombre,
                'pvp' => $comida->pvp ?? $comida->producto->pvp,
                'stock' => $comida->stock ?? $comida->producto->stock,
                'disponible' => $comida->disponible ?? $comida->producto->disponible,
                'precioCompra' => $comida->precioCompra ?? $comida->producto->precioCompra,
            ]);
        }
    }

    /**
     * Handle the Comida "deleted" event.
     */
    public function deleted(Comida $comida): void
    {
        // Eliminar el producto cuando se elimina la comida
        if ($comida->producto) {
            $comida->producto->delete();
        }
    }
}

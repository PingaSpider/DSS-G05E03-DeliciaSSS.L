<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['cod', 'pvp', 'nombre','stock','precioCompra','imagen_url'];
    protected $primaryKey = 'cod';
    protected $keyType = 'string';

    public $incrementing = false;

    public function lineasPedido()
    {
        return $this->hasMany(LineaPedido::class);
    }

    // Relaciones con las subclases
    public function comida()
    {
        return $this->hasOne(Comida::class, 'cod', 'cod');
    }

    public function bebida()
    {
        return $this->hasOne(Bebida::class, 'cod', 'cod');
    }

    public function menu()
    {
        return $this->hasOne(Menu::class, 'cod', 'cod');
    }

    /**
     * Accessor para obtener la URL de la imagen
     */
    public function getImagenUrlAttribute($value)
    {   
        // Si es un menú, usar imagen predeterminada para menús
        if (substr($this->cod, 0, 1) === 'M') {
            return asset('assets/images/comida/menus/menu-por-defecto.png');
        }
        // Si hay un valor, lo usamos para construir la ruta completa
        if ($value) {
            $categoria = $this->determinarCategoria();
            
            // Intentamos encontrar la imagen con diferentes extensiones
            $extensiones = ['png', 'jpg', 'jpeg', 'webp'];
            
            foreach ($extensiones as $ext) {
                $path = "assets/images/comida/{$categoria}/{$value}.{$ext}";
                if (file_exists(public_path($path))) {
                    return asset($path);
                }
            }
        }

        // Si no se encuentra la imagen, devolvemos una por defecto
        return asset('assets/images/comida/no-encontrado.png');
    }

    /**
     * Determina la categoría del producto basado en el código o nombre
     */
    protected function determinarCategoria()
    {
        $codigo = strtolower($this->cod);
        $nombre = strtolower($this->nombre);
        
        // Basado en el código
        if (str_starts_with($codigo, 'b')) {
            return 'bebida';
        }
        if (str_starts_with($codigo, 'm')) {
            return 'menus';
        }

        // Si no podemos determinar por el código, usar el nombre
        if (str_contains($nombre, 'pizza')) {
            return 'pizza';
        }
        if (str_contains($nombre, 'burger') || str_contains($nombre, 'patatas') || str_contains($nombre, 'hamburguesa')) {
            return 'hamburguesa';
        }
        if (str_contains($nombre, 'desayuno') || str_contains($nombre, 'croissant') || str_contains($nombre, 'pancakes')) {
            return 'desayuno';
        }
        if (str_contains($nombre, 'postre') || str_contains($nombre, 'tarta') || str_contains($nombre, 'helado')) {
            return 'postre';
        }
        if (str_contains($nombre, 'combinado')) {
            return 'combinado';
        }

        // Por defecto, retornar comida
        return 'comida';
    }

    /**
     * Mutator para establecer la URL de la imagen
     */
    public function setImagenUrlAttribute($value)
    {
        // Si se proporciona una URL vacía, la establecemos como null
        $this->attributes['imagen_url'] = empty($value) ? null : $value;
    }
}
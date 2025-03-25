<?php

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\BebidaController;
use App\Http\Controllers\ComidaController;
use App\Http\Controllers\MenuProductoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\LineaPedidoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReservaController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

// Rutas temporales para los enlaces del menú
Route::get('/home', function () { return view("home"); })->name('home');
Route::get('/reserva', function () { return view("reserva"); })->name('reserva');
Route::get('/menu', function () { return view("menu"); })->name('menu');
Route::get('/contacto', function () { return view("contacto"); })->name('contacto');

// Ruta para procesar el formulario de reserva
Route::post('/reservas', function () { 
    return "Reserva recibida"; 
})->name('reservas.store');

// Rutas para Producto
Route::resource('productos', ProductoController::class);

// Rutas para Bebida
Route::resource('bebidas', BebidaController::class);

// Rutas para Comida
Route::resource('comidas', ComidaController::class);

// Rutas para Menu
Route::resource('menus', MenuController::class);

// Rutas para MenuProducto (relación)
Route::resource('menu-productos', MenuProductoController::class);

// Rutas adicionales para relaciones de menú-producto
Route::get('menus/{menu}/add-producto', [MenuController::class, 'addProducto'])
    ->name('menus.add-producto');

Route::post('menus/{menu}/attach-producto', [MenuController::class, 'attachProducto'])
    ->name('menus.attach-producto');

Route::delete('menus/{menu}/detach-producto/{producto}', [MenuController::class, 'detachProducto'])
    ->name('menus.detach-producto');

// Rutas personalizadas para Mesa - CORREGIDAS
Route::get('mesas', [MesaController::class, 'index'])->name('mesas.index');
Route::get('mesas/create', [MesaController::class, 'create'])->name('mesas.create');
Route::post('mesas', [MesaController::class, 'store'])->name('mesas.store');
Route::get('mesas/{codMesa}', [MesaController::class, 'show'])->name('mesas.show');
Route::get('mesas/{codMesa}/edit', [MesaController::class, 'edit'])->name('mesas.edit');
Route::put('mesas/{codMesa}', [MesaController::class, 'update'])->name('mesas.update');
Route::delete('mesas/{codMesa}', [MesaController::class, 'destroy'])->name('mesas.destroy');
Route::get('mesas/{codMesa}/delete', [MesaController::class, 'delete'])->name('mesas.delete');

// Rutas para LineaPedido
Route::resource('linea-pedidos', LineaPedidoController::class);

// Rutas para Usuario
Route::resource('usuarios', UsuarioController::class);

// Rutas para Reserva
Route::resource('reservas', ReservaController::class);

// Rutas para Pedido
Route::resource('pedidos', PedidoController::class);
//peidido create,edit,update,destroy,store
Route::get('pedidos/create', [PedidoController::class, 'create'])->name('pedidos.create');
Route::post('pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
Route::get('pedidos/{cod}/edit', [PedidoController::class, 'edit'])->name('pedidos.edit');
Route::put('pedidos/{cod}', [PedidoController::class, 'update'])->name('pedidos.update');
Route::delete('pedidos/{cod}', [PedidoController::class, 'destroy'])->name('pedidos.destroy');


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
    return view('welcome');
});

// Rutas temporales para los enlaces del menú
/*
Route::get('/home', function () { return view("home"); })->name('home');
Route::get('/reserva', function () { return view("reserva"); })->name('reserva');
Route::get('/menu', function () { return view("menu"); })->name('menu');
Route::get('/contacto', function () { return view("contacto"); })->name('contacto');
Route::get('/carrito', function () { return view("carrito"); })->name('carrito');
Route::get('/confirmacionreserva', function () { return view("confirmacionreserva"); })->name('confirmacionreserva');
Route::get('/user', function () { return view("user"); })->name('user');
Route::get('/producto', function () { return view("producto"); })->name('producto');
Route::get('/registro', function () { return view("registro"); })->name('registro');
Route::get('/login', function () { return view("login"); })->name('login');
*/


// Rutas para el CRUD de usuarios
Route::get('/usuarios', [UsuarioController::class, 'paginate'])->name('usuarios.paginate');
Route::get('/usuarios/create', [UsuarioController::class, 'create_get'])->name('usuarios.create');
Route::post('/usuarios', [UsuarioController::class, 'store'])->name('user.store');
Route::get('/usuarios/{id}', [UsuarioController::class, 'show_get'])->name('usuarios.show');
Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

// Ruta para verificar email
Route::get('/verificar-email', [UsuarioController::class, 'verificarEmail'])->name('usuarios.verificar-email');


// Rutas para el CRUD de mesas
Route::get('/mesas', [MesaController::class, 'paginate'])->name('mesas.paginate');
Route::get('/mesas/create', [MesaController::class, 'create'])->name('mesas.create');
Route::post('/mesas', [MesaController::class, 'store'])->name('mesas.store');
Route::get('/mesas/{codMesa}', [MesaController::class, 'show'])->name('mesas.show');
Route::get('/mesas/{codMesa}/edit', [MesaController::class, 'edit'])->name('mesas.edit');
Route::put('/mesas/{codMesa}', [MesaController::class, 'update'])->name('mesas.update');
Route::delete('/mesas/{codMesa}', [MesaController::class, 'destroy'])->name('mesas.destroy');

// Ruta para verificar código de mesa
Route::get('/verificar-codigo-mesa', [MesaController::class, 'verificarCodigo'])->name('mesas.verificar-codigo');

// Para mantener compatibilidad con las rutas antiguas
Route::get('/mesas/index', [MesaController::class, 'index'])->name('mesas.index');


// Rutas para el CRUD de pedidos
Route::get('/pedidos', [PedidoController::class, 'paginate'])->name('pedidos.paginate');
Route::get('/pedidos/create', [PedidoController::class, 'create'])->name('pedidos.create');
Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
Route::get('/pedidos/{cod}', [PedidoController::class, 'show'])->name('pedidos.show');
Route::get('/pedidos/{cod}/edit', [PedidoController::class, 'edit'])->name('pedidos.edit');
Route::put('/pedidos/{cod}', [PedidoController::class, 'update'])->name('pedidos.update');
Route::delete('/pedidos/{cod}', [PedidoController::class, 'destroy'])->name('pedidos.destroy');

// Ruta para verificar código de pedido
Route::get('/verificar-codigo-pedido', [PedidoController::class, 'verificarCodigo'])->name('pedidos.verificar-codigo');

// Para mantener compatibilidad con las rutas antiguas
Route::get('/pedidos/index', [PedidoController::class, 'index'])->name('pedidos.index');


// Rutas para el CRUD de líneas de pedido
Route::get('/lineas-pedido', [LineaPedidoController::class, 'paginate'])->name('lineaPedidos.paginate');
Route::get('/lineas-pedido/create', [LineaPedidoController::class, 'create'])->name('lineaPedidos.create');
Route::post('/lineas-pedido', [LineaPedidoController::class, 'store'])->name('lineaPedidos.store');
Route::get('/lineas-pedido/{linea}', [LineaPedidoController::class, 'show'])->name('lineaPedidos.show');
Route::get('/lineas-pedido/{linea}/edit', [LineaPedidoController::class, 'edit'])->name('lineaPedidos.edit');
Route::put('/lineas-pedido/{linea}', [LineaPedidoController::class, 'update'])->name('lineaPedidos.update');
Route::delete('/lineas-pedido/{linea}', [LineaPedidoController::class, 'destroy'])->name('lineaPedidos.destroy');

// Ruta para verificar código de línea de pedido
Route::get('/verificar-codigo-linea', [LineaPedidoController::class, 'verificarCodigo'])->name('lineaPedidos.verificar-codigo');

// Ruta para crear línea de pedido directamente desde un pedido
Route::get('/pedidos/{pedido_id}/lineas/create', [LineaPedidoController::class, 'createForPedido'])->name('lineaPedidos.createForPedido');

// Rutas para el CRUD de productos
Route::get('/productos', [ProductoController::class, 'paginate'])->name('productos.paginate');
Route::get('/productos/create', [ProductoController::class, 'create_get'])->name('productos.create');
Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
Route::get('/productos/{cod}', [ProductoController::class, 'show_get'])->name('productos.show');
Route::get('/productos/{cod}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
Route::put('/productos/{cod}', [ProductoController::class, 'update'])->name('productos.update');
Route::delete('/productos/{cod}', [ProductoController::class, 'destroy'])->name('productos.destroy');

// Rutas adicionales para productos
Route::post('/productos/create', [ProductoController::class, 'create_post'])->name('productos.create_post');
Route::post('/productos/show', [ProductoController::class, 'show_post'])->name('productos.show_post');
Route::get('/productos/search', [ProductoController::class, 'search'])->name('productos.search');
Route::get('/verificar-codigo-producto', [ProductoController::class, 'verificarCodigo'])->name('productos.verificarCodigo');

// Rutas para el CRUD de comidas
Route::prefix('comida')->group(function () {
    Route::get('/', [ComidaController::class, 'paginate'])->name('comida.paginate');
    Route::get('/create', [ComidaController::class, 'create_get'])->name('comida.create');
    Route::post('/store', [ComidaController::class, 'store'])->name('comida.store');
    Route::get('/{cod}', [ComidaController::class, 'show_get'])->name('comida.show');
    Route::get('/{cod}/edit', [ComidaController::class, 'edit'])->name('comida.edit');
    Route::put('/{cod}', [ComidaController::class, 'update'])->name('comida.update');
    Route::delete('/{cod}', [ComidaController::class, 'destroy'])->name('comida.destroy');
    
    // Rutas de compatibilidad
    Route::post('/create', [ComidaController::class, 'create_post'])->name('comida.create_post');
    Route::post('/show_post', [ComidaController::class, 'show_post'])->name('comida.show_post');
    // Rutas de utilidad
    Route::get('/verificar-codigo', [ComidaController::class, 'verificarCodigo'])->name('comida.verificar-codigo');
    Route::post('/search', [ComidaController::class, 'search'])->name('comida.search');
});

// Rutas para el CRUD de bebidas
Route::prefix('bebidas')->group(function () {
    Route::get('/', [BebidaController::class, 'paginate'])->name('bebidas.paginate');
    Route::get('/create', [BebidaController::class, 'create_get'])->name('bebidas.create');
    Route::post('/store', [BebidaController::class, 'store'])->name('bebidas.store');
    Route::get('/{cod}', [BebidaController::class, 'show'])->name('bebidas.show');
    Route::get('/{cod}/edit', [BebidaController::class, 'edit'])->name('bebidas.edit');
    Route::put('/{cod}', [BebidaController::class, 'update'])->name('bebidas.update');
    Route::delete('/{cod}', [BebidaController::class, 'destroy'])->name('bebidas.destroy');
    
    // Rutas de compatibilidad
    Route::post('/create_post', [BebidaController::class, 'create_post'])->name('bebidas.create_post');
    Route::post('/show_post', [BebidaController::class, 'show_post'])->name('bebidas.show_post');
    
    // Rutas de utilidad
    Route::get('/verificar-codigo', [BebidaController::class, 'verificarCodigo'])->name('bebidas.verificar-codigo');
    Route::post('/search', [BebidaController::class, 'search'])->name('bebidas.search');
    // Alias para delete (compatibilidad)
    Route::get('/{cod}/delete', [BebidaController::class, 'delete'])->name('bebidas.delete');
});

// Rutas para el CRUD de menús
Route::prefix('menus')->group(function () {
    Route::get('/', [MenuController::class, 'paginate'])->name('menus.paginate');
    Route::get('/create', [MenuController::class, 'create_get'])->name('menus.create');
    Route::post('/store', [MenuController::class, 'store'])->name('menus.store');
    Route::get('/{cod}', [MenuController::class, 'show'])->name('menus.show');
    Route::get('/{cod}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('/{cod}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/{cod}', [MenuController::class, 'destroy'])->name('menus.destroy');
    
    // Rutas de compatibilidad
    Route::post('/create_post', [MenuController::class, 'create_post'])->name('menus.create_post');
    Route::post('/show_post', [MenuController::class, 'show_post'])->name('menus.show_post');
    
    // Rutas de utilidad
    Route::get('/verificar-codigo', [MenuController::class, 'verificarCodigo'])->name('menus.verificar-codigo');
    Route::post('/search', [MenuController::class, 'search'])->name('menus.search');
    // Alias para delete (compatibilidad)
    Route::get('/{cod}/delete', [MenuController::class, 'delete'])->name('menus.delete');
});
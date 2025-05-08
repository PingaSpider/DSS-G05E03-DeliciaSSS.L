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
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebProductoController;
use App\Http\Controllers\WebMenuController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistroController;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\WebReservaController;
use App\Http\Controllers\WebPedidoController;
use App\Http\Controllers\WebLineaPedidoController;

use Illuminate\Support\Facades\Route;

// ====================================
// RUTAS FRONTEND (Clientes)
// ====================================

// Página principal (home) del frontend
Route::get('/', function () {
    return view('home');
})->name('home');

// Rutas de autenticación del frontend
Route::get('/login', [LoginController::class, 'mostrar'])
    ->name('login.form')
    ->middleware('guest');
    
Route::post('/login', [LoginController::class, 'autenticar'])
    ->name('login')
    ->middleware('guest');

Route::post('/logout', [LoginController::class, 'cerrarSesion'])
    ->name('logout')
    ->middleware('auth');

Route::get('/registro', [RegistroController::class, 'mostrar'])
    ->name('registro.form')
    ->middleware('guest');
    
Route::post('/registro', [RegistroController::class, 'registrar'])
    ->name('registro')
    ->middleware('guest');

// Menú público
Route::get('/menu', [WebMenuController::class, 'index'])->name('menu');

// Productos públicos
Route::prefix('producto')->group(function () {
    Route::get('/{cod?}', [WebProductoController::class, 'show'])
        ->name('producto.show');
});

// Carrito - Requiere autenticación
Route::middleware('auth')->group(function () {
    Route::get('/carrito', [WebPedidoController::class, 'viewCart'])->name('carrito.view');
    Route::post('/carrito/checkout', [WebPedidoController::class, 'checkout'])->name('carrito.checkout');
    Route::post('/carrito/update-shipping', [WebPedidoController::class, 'updateShippingDetails'])->name('carrito.updateShipping');
    
    // Operaciones con items del carrito
    Route::post('/carrito/add', [WebLineaPedidoController::class, 'addToCart'])->name('carrito.add');
    Route::post('/carrito/update', [WebLineaPedidoController::class, 'updateQuantity'])->name('carrito.update');
    Route::delete('/carrito/remove/{lineaId}', [WebLineaPedidoController::class, 'removeFromCart'])->name('carrito.remove');

});

Route::get('/api/productos/buscar', [WebProductoController::class, 'buscar'])->name('api.productos.buscar');

// Perfil de usuario
Route::prefix('mi-perfil')->middleware('auth')->group(function () {
    Route::get('/', [UserController::class, 'profile'])
        ->name('user.profile');
    
    Route::put('/actualizar', [UserController::class, 'updateProfile'])
        ->name('user.updateProfile');

    // Pedidos
    Route::get('/pedidos', [UserController::class, 'orders'])
        ->name('user.orders');
    
    Route::get('/pedidos/{orderId}', [UserController::class, 'showOrder'])
        ->name('user.showOrder');
    
    Route::post('/pedidos/{orderId}/repetir', [UserController::class, 'repeatOrder'])
        ->name('user.repeatOrder');
    
    Route::get('/pedidos/{orderId}/cancelar', [UserController::class, 'cancelOrder'])
        ->name('user.cancelOrder');
});

// Reservaciones frontend
Route::prefix('reservaciones')->middleware('auth')->group(function () {
    Route::get('/', [WebReservaController::class, 'index'])
        ->name('reservaciones.index');
    
    Route::post('/', [WebReservaController::class, 'store'])
        ->name('reservaciones.store');
    
    Route::get('/confirmacion/{id}', [WebReservaController::class, 'confirmacion'])
        ->name('reservaciones.confirmacion');
    
    Route::delete('/{id}', [WebReservaController::class, 'cancelar'])
        ->name('reservaciones.cancelar');
    
    Route::get('/mis-reservaciones', [WebReservaController::class, 'misReservas'])
        ->name('reservaciones.mis-reservaciones');

});

// ====================================
// RUTAS PANEL ADMIN
// ====================================

Route::prefix('admin')->middleware('auth')->group(function () {
    // Dashboard admin
    Route::get('/', function () {
        return view('paneladmin');
    })->name('paneladmin');
    
    // CRUD Usuarios
    Route::prefix('usuarios')->group(function () {
        Route::get('/', [UsuarioController::class, 'paginate'])->name('usuarios.paginate');
        Route::get('/create', [UsuarioController::class, 'create_get'])->name('usuarios.create');
        Route::post('/', [UsuarioController::class, 'store'])->name('user.store');
        Route::get('/{id}', [UsuarioController::class, 'show_get'])->name('usuarios.show');
        Route::get('/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
        Route::get('/verificar-email', [UsuarioController::class, 'verificarEmail'])->name('usuarios.verificar-email');
    });

    // CRUD Mesas
    Route::prefix('mesas')->group(function () {
        Route::get('/', [MesaController::class, 'paginate'])->name('mesas.paginate');
        Route::get('/create', [MesaController::class, 'create'])->name('mesas.create');
        Route::post('/', [MesaController::class, 'store'])->name('mesas.store');
        Route::get('/{codMesa}', [MesaController::class, 'show'])->name('mesas.show');
        Route::get('/{codMesa}/edit', [MesaController::class, 'edit'])->name('mesas.edit');
        Route::put('/{codMesa}', [MesaController::class, 'update'])->name('mesas.update');
        Route::delete('/{codMesa}', [MesaController::class, 'destroy'])->name('mesas.destroy');
        Route::get('/verificar-codigo-mesa', [MesaController::class, 'verificarCodigo'])->name('mesas.verificar-codigo');
    });

    // CRUD Pedidos
    Route::prefix('pedidos')->group(function () {
        Route::get('/', [PedidoController::class, 'paginate'])->name('pedidos.paginate');
        Route::get('/create', [PedidoController::class, 'create'])->name('pedidos.create');
        Route::post('/', [PedidoController::class, 'store'])->name('pedidos.store');
        Route::get('/{cod}', [PedidoController::class, 'show'])->name('pedidos.show');
        Route::get('/{cod}/edit', [PedidoController::class, 'edit'])->name('pedidos.edit');
        Route::put('/{cod}', [PedidoController::class, 'update'])->name('pedidos.update');
        Route::delete('/{cod}', [PedidoController::class, 'destroy'])->name('pedidos.destroy');
        Route::get('/verificar-codigo-pedido', [PedidoController::class, 'verificarCodigo'])->name('pedidos.verificar-codigo');
    });

    // CRUD Líneas de pedido
    Route::prefix('lineas-pedido')->group(function () {
        Route::get('/', [LineaPedidoController::class, 'paginate'])->name('lineaPedidos.paginate');
        Route::get('/create', [LineaPedidoController::class, 'create'])->name('lineaPedidos.create');
        Route::post('/', [LineaPedidoController::class, 'store'])->name('lineaPedidos.store');
        Route::get('/{linea}', [LineaPedidoController::class, 'show'])->name('lineaPedidos.show');
        Route::get('/{linea}/edit', [LineaPedidoController::class, 'edit'])->name('lineaPedidos.edit');
        Route::put('/{linea}', [LineaPedidoController::class, 'update'])->name('lineaPedidos.update');
        Route::delete('/{linea}', [LineaPedidoController::class, 'destroy'])->name('lineaPedidos.destroy');
        Route::get('/verificar-codigo-linea', [LineaPedidoController::class, 'verificarCodigo'])->name('lineaPedidos.verificar-codigo');
    });

    // CRUD Productos
    Route::prefix('productos')->group(function () {
        Route::get('/', [ProductoController::class, 'paginate'])->name('productos.paginate');
        Route::get('/create', [ProductoController::class, 'create_get'])->name('productos.create');
        Route::post('/', [ProductoController::class, 'store'])->name('productos.store');
        Route::get('/{cod}', [ProductoController::class, 'show_get'])->name('productos.show');
        Route::get('/{cod}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
        Route::put('/{cod}', [ProductoController::class, 'update'])->name('productos.update');
        Route::delete('/{cod}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    });

    // CRUD Comidas
    Route::prefix('comidas')->group(function () {
        Route::get('/', [ComidaController::class, 'paginate'])->name('comida.paginate');
        Route::get('/create', [ComidaController::class, 'create_get'])->name('comida.create');
        Route::post('/store', [ComidaController::class, 'store'])->name('comida.store');
        Route::get('/{cod}', [ComidaController::class, 'show_get'])->name('comida.show');
        Route::get('/{cod}/edit', [ComidaController::class, 'edit'])->name('comida.edit');
        Route::put('/{cod}', [ComidaController::class, 'update'])->name('comida.update');
        Route::delete('/{cod}', [ComidaController::class, 'destroy'])->name('comida.destroy');
    });

    // CRUD Bebidas
    Route::prefix('bebidas')->group(function () {
        Route::get('/', [BebidaController::class, 'paginate'])->name('bebidas.paginate');
        Route::get('/create', [BebidaController::class, 'create_get'])->name('bebidas.create');
        Route::post('/store', [BebidaController::class, 'store'])->name('bebidas.store');
        Route::get('/{cod}', [BebidaController::class, 'show'])->name('bebidas.show');
        Route::get('/{cod}/edit', [BebidaController::class, 'edit'])->name('bebidas.edit');
        Route::put('/{cod}', [BebidaController::class, 'update'])->name('bebidas.update');
        Route::delete('/{cod}', [BebidaController::class, 'destroy'])->name('bebidas.destroy');
    });

    // CRUD Menús
    Route::prefix('menus')->group(function () {
        Route::get('/', [MenuController::class, 'paginate'])->name('menus.paginate');
        Route::get('/create', [MenuController::class, 'create_get'])->name('menus.create');
        Route::post('/store', [MenuController::class, 'store'])->name('menus.store');
        Route::get('/{cod}', [MenuController::class, 'show'])->name('menus.show');
        Route::get('/{cod}/edit', [MenuController::class, 'edit'])->name('menus.edit');
        Route::put('/{cod}', [MenuController::class, 'update'])->name('menus.update');
        Route::delete('/{cod}', [MenuController::class, 'destroy'])->name('menus.destroy');
    });

    // CRUD Reservas
    Route::prefix('reservas')->group(function () {
        Route::get('/', [ReservaController::class, 'paginate'])->name('reservas.paginate');
        Route::get('/create', [ReservaController::class, 'create'])->name('reservas.create');
        Route::post('/store', [ReservaController::class, 'store'])->name('reservas.store');
        Route::get('/{codReserva}', [ReservaController::class, 'show'])->name('reservas.show');
        Route::get('/{codReserva}/edit', [ReservaController::class, 'edit'])->name('reservas.edit');
        Route::put('/{codReserva}', [ReservaController::class, 'update'])->name('reservas.update');
        Route::delete('/{codReserva}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
    });
});

// Para cargar imágenes por categoría
Route::get('/api/comida/images/{category}', [ComidaController::class, 'getImagesByCategory'])
    ->name('api.comida.images');
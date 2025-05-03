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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('paneladmin');
})->name('paneladmin');


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

// Rutas para el CRUD para reservas
Route::prefix('reservas')->group(function () {
    Route::get('/', [ReservaController::class, 'paginate'])->name('reservas.paginate');
    Route::get('/create', [ReservaController::class, 'create'])->name('reservas.create');  // Corregido: 'create' en lugar de 'create_get'
    Route::post('/store', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/{codReserva}', [ReservaController::class, 'show'])->name('reservas.show');  // Corregido: 'codReserva' en lugar de 'cod'
    Route::get('/{codReserva}/edit', [ReservaController::class, 'edit'])->name('reservas.edit');  // Corregido: 'codReserva' en lugar de 'cod'
    Route::put('/{codReserva}', [ReservaController::class, 'update'])->name('reservas.update');  // Corregido: 'codReserva' en lugar de 'cod'
    Route::delete('/{codReserva}', [ReservaController::class, 'destroy'])->name('reservas.destroy');  // Corregido: 'codReserva' en lugar de 'cod'
    
    // Rutas para acciones adicionales
    Route::get('/{codReserva}/confirmar', [ReservaController::class, 'confirmarReserva'])->name('reservas.confirmar');
    Route::get('/{codReserva}/cancelar', [ReservaController::class, 'cancelarReserva'])->name('reservas.cancelar');
    Route::get('/mesas-disponibles', [ReservaController::class, 'getMesasDisponibles'])->name('reservas.mesas-disponibles');
    
    // Rutas de utilidad
    Route::get('/verificar-codigo', [ReservaController::class, 'verificarCodigo'])->name('reservas.verificar-codigo');
});

//ENTREGA 3 :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// Rutas para el perfil y funcionalidades del usuario en la web pública

// Perfil de usuario - Punto central después de login
Route::prefix('mi-perfil')->group(function () {
    Route::get('/', [UserController::class, 'profile'])
        ->name('user.profile')
        ->middleware('auth'); // Protege la ruta
    
    Route::put('/actualizar', [UserController::class, 'updateProfile'])
        ->name('user.updateProfile')
        ->middleware('auth');

    // Pedidos
    Route::get('/pedidos', [UserController::class, 'orders'])
        ->name('user.orders')
        ->middleware('auth');
    
    Route::get('/pedidos/{orderId}', [UserController::class, 'showOrder'])
        ->name('user.showOrder')
        ->middleware('auth');
    
    Route::post('/pedidos/{orderId}/repetir', [UserController::class, 'repeatOrder'])
        ->name('user.repeatOrder')
        ->middleware('auth');
    
    Route::get('/pedidos/{orderId}/cancelar', [UserController::class, 'cancelOrder'])
        ->name('user.cancelOrder')
        ->middleware('auth');
});

// Rutas para autenticación
// Nota: Estas rutas reemplazan las existentes en el grupo 'mi-perfil'
Route::post('/logout', [LoginController::class, 'cerrarSesion'])
    ->name('logout')
    ->middleware('auth');

Route::get('/login', [LoginController::class, 'mostrar'])
    ->name('login.form')
    ->middleware('guest');
    
Route::post('/login', [LoginController::class, 'autenticar'])
    ->name('login')
    ->middleware('guest');

// Rutas para registro
Route::get('/registro', [RegistroController::class, 'mostrar'])
    ->name('registro.form')
    ->middleware('guest');
    
Route::post('/registro', [RegistroController::class, 'registrar'])
    ->name('registro')
    ->middleware('guest');

// Redirección explícita para /home
Route::redirect('/home', '/mi-perfil');

//Ruta para la pagina home principal (acceso público)
Route::get('/home', function () {
    return view('home');
})->name('home');

//Ruta para la pagina de menu (acceso público)
Route::get('/menu', [WebMenuController::class, 'index'])->name('menu');

// Rutas para productos en el frontend (acceso público)
Route::prefix('producto')->group(function () {
    // Ruta para mostrar la lista de productos o un producto específico
    Route::get('/{cod?}', [WebProductoController::class, 'show'])
        ->name('producto.show');

    // Ruta para añadir al carrito (protegida)
    Route::post('/add-to-cart', [WebProductoController::class, 'addToCart'])
        ->middleware('auth')
        ->name('producto.addToCart');

    // Ruta para toggle de wishlist (protegida)
    Route::post('/toggle-wishlist/{productoId}', [WebProductoController::class, 'toggleWishlist'])
        ->middleware('auth')
        ->name('producto.toggleWishlist');
});

// Para mantener compatibilidad con las rutas que tienes para el UserController
// Estas rutas serán redirigidas a las nuevas rutas de autenticación
Route::prefix('mi-perfil')->group(function () {
    // Redirecciones para evitar duplicidad
    Route::get('/registro', function() {
        return redirect()->route('registro.form');
    });
    
    Route::post('/registro', function() {
        return redirect()->route('registro');
    });
    
    Route::get('/login', function() {
        return redirect()->route('login.form');
    });
    
    Route::post('/login', function() {
        return redirect()->route('login');
    });
    
    Route::post('/logout', function() {
        return redirect()->route('logout');
    });

    // Mantén ruta para verificar email si es necesario
    Route::get('/verificar-email', [UserController::class, 'checkEmail'])
        ->name('user.checkEmail');
});

// Rutas para reservaciones en el frontend
Route::prefix('reservaciones')->middleware('auth')->group(function () {
    // Muestra el formulario de reserva
    Route::get('/', [App\Http\Controllers\WebReservaController::class, 'index'])
        ->name('reservaciones.index');
    
    // Procesa la reserva
    Route::post('/', [App\Http\Controllers\WebReservaController::class, 'store'])
        ->name('reservaciones.store');
    
    // Página de confirmación de reserva
    Route::get('/confirmacion/{id}', [App\Http\Controllers\WebReservaController::class, 'confirmacion'])
        ->name('reservaciones.confirmacion');
    
    // Cancelar una reserva
    Route::delete('/{id}', [App\Http\Controllers\WebReservaController::class, 'cancelar'])
        ->name('reservaciones.cancelar');
    
    // Ver mis reservas
    Route::get('/mis-reservaciones', [App\Http\Controllers\WebReservaController::class, 'misReservas'])
        ->name('reservaciones.mis-reservaciones');
});

// Redireccionamiento para mantener compatibilidad con URLs actuales
Route::get('/reserva', function() {
    return redirect()->route('reservaciones.index'); 
});

Route::post('/reserva', function() {
    return redirect()->route('reservaciones.store');
});

Route::get('/reserva/confirmacion/{id}', function($id) {
    return redirect()->route('reservaciones.confirmacion', ['id' => $id]);
});

Route::get('/mis-reservas', function() {
    return redirect()->route('reservaciones.mis-reservaciones');
});
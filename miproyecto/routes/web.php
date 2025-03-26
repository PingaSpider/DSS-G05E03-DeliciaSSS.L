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
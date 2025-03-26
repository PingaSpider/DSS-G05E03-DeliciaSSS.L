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


//Rutas de acceso
Route::get('foo/user/create', [UsuarioController::class, 'create_get']);
Route::post('foo/user/create', [UsuarioController::class, 'create_post']);


Route::post('foo/user/store', [UsuarioController::class, 'store'])->name('user.store');
Route::get('foo/user/show', [UsuarioController::class, 'show'])->name('post.show');

Route::get('foo/user/paginate', [UsuarioController::class, 'paginate'])->name('user.paginate');


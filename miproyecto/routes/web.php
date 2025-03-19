<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


//Route de Bebida
Route::get('/bebida/create', 'App\Http\Controllers\BebidaController@create');
Route::post('/bebida/store', 'App\Http\Controllers\BebidaController@store');
Route::get('/bebida/edit/{cod}', 'App\Http\Controllers\BebidaController@edit');
Route::post('/bebida/update/{cod}', 'App\Http\Controllers\BebidaController@update');

//Route de Comida
Route::get('/comida/create', 'App\Http\Controllers\ComidaController@create');
Route::post('/comida/store', 'App\Http\Controllers\ComidaController@store');
Route::get('/comida/edit/{cod}', 'App\Http\Controllers\ComidaController@edit');
Route::post('/comida/update/{cod}', 'App\Http\Controllers\ComidaController@update');

//Route de Producto
Route::get('/producto/create', 'App\Http\Controllers\ProductoController@create');
Route::post('/producto/store', 'App\Http\Controllers\ProductoController@store');
Route::get('/producto/edit/{cod}', 'App\Http\Controllers\ProductoController@edit');
Route::post('/producto/update/{cod}', 'App\Http\Controllers\ProductoController@update');

//Route de Menu
Route::get('/menu/create', 'App\Http\Controllers\MenuController@create');
Route::post('/menu/store', 'App\Http\Controllers\MenuController@store');
Route::get('/menu/edit/{cod}', 'App\Http\Controllers\MenuController@edit');
Route::post('/menu/update/{cod}', 'App\Http\Controllers\MenuController@update');

//Route de Mesa
Route::get('/mesa/create', 'App\Http\Controllers\MesaController@create');
Route::post('/mesa/store', 'App\Http\Controllers\MesaController@store');
Route::get('/mesa/edit/{cod}', 'App\Http\Controllers\MesaController@edit');
Route::post('/mesa/update/{cod}', 'App\Http\Controllers\MesaController@update');

//Route de Pedido
Route::get('/pedido/create', 'App\Http\Controllers\PedidoController@create');
Route::post('/pedido/store', 'App\Http\Controllers\PedidoController@store');
Route::get('/pedido/edit/{cod}', 'App\Http\Controllers\PedidoController@edit');
Route::post('/pedido/update/{cod}', 'App\Http\Controllers\PedidoController@update');

//Route de Reserva
Route::get('/reserva/create', 'App\Http\Controllers\ReservaController@create');
Route::post('/reserva/store', 'App\Http\Controllers\ReservaController@store');
Route::get('/reserva/edit/{id}', 'App\Http\Controllers\ReservaController@edit');
Route::post('/reserva/update/{id}', 'App\Http\Controllers\ReservaController@update');

//Route de Usuario
Route::get('/usuario/create', 'App\Http\Controllers\UsuarioController@create');
Route::post('/usuario/store', 'App\Http\Controllers\UsuarioController@store');
Route::get('/usuario/edit/{id}', 'App\Http\Controllers\UsuarioController@edit');
Route::post('/usuario/update/{id}', 'App\Http\Controllers\UsuarioController@update');

//Route de LineaPedido
Route::get('/lineaPedido/create', 'App\Http\Controllers\LineaPedidoController@create');
Route::post('/lineaPedido/store', 'App\Http\Controllers\LineaPedidoController@store');
Route::get('/lineaPedido/edit/{cod}', 'App\Http\Controllers\LineaPedidoController@edit');
Route::post('/lineaPedido/update/{cod}', 'App\Http\Controllers\LineaPedidoController@update');

//Route de MenuProducto
Route::get('/menuProducto/create', 'App\Http\Controllers\MenuProductoController@create');
Route::post('/menuProducto/store', 'App\Http\Controllers\MenuProductoController@store');
Route::get('/menuProducto/edit/{cod}', 'App\Http\Controllers\MenuProductoController@edit');
Route::post('/menuProducto/update/{cod}', 'App\Http\Controllers\MenuProductoController@update');








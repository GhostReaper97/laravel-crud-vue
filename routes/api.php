<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/usuario','UsuarioController@Listar');
Route::get('/usuario/{IdUsuario}', 'UsuarioController@Buscar');

Route::post('/usuario','UsuarioController@Agregar');
Route::post('/usuario/update','UsuarioController@Actualizar');
Route::post('/usuario/delete','UsuarioController@Eliminar');

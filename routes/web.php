<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/** Ruta por defecto de Laravel (se puede sobreescribir) */
Route::get('/', function () {
    return view('welcome');
});

/** Ruta con parámetro opcional */
Route::get('/bienvenido/{nombre?}', function ($nombre = 'Desconocido') {
    return 'Bienvenido ' . $nombre;
});

/** Ruta con parámetro obligatorio */
Route::get('/Hola/{nombre}', function ($nombre) {
    //return response()->json(['mensaje' => 'Hola mundo!']);
    return 'Bienvenido ' . $nombre;
});

/** Ruta para obtener el formulario del producto, invocando al controlador */
Route::get('/formulario-producto/{id}', 'ProductController@get')->name('form.product');

/** Ruta para publicar el producto, invocando al controladro */
Route::post('/publica-producto', 'ProductController@post')->name('post.product');

/**
 * Agrupar rutas con una url base fija
 * as -> nombre con el que se hará referencia en name
 * prefix -> nombre de la uri
 */
Route::group(['as' => 'cremas.', 'prefix' => 'cremas'], function () {
    Route::get('detalle/{url_categoria}/{url_crema}', function ($url_categoria, $url_crema) {
        return 'Este es el detalle de la crema ' . $url_crema . ' de la categoria ' . $url_categoria;
    })->name('detail');

    Route::get('landing-page', function () {
        return 'Esta es la landing de cremas';
    })->name('landing');
});

Route::get('login-admin', function () {
    /** Guardando la variable de sesión administrador */
    session()->put('administrador', ['name' => 'Rod', 'email' => 'rod@codice.com']);
    return 'Accede';
})->name('login.admon');

Route::get('logout-admin', function () {
    /** Eliminando la variable de sesión administrador y hacer un redirect al login */
    session()->forget('administrador');
    return redirect()->route('login.admon');
})->name('logout.admon');

/** Grupo de rutas admin con middleware que verifica que el administrador tenga una sesión activa */
Route::group(['as' => 'admon.', 'prefix' => 'admon', 'middleware' => 'auth.admin'], function () {
    Route::get('edicion', function () {
        $administrador = session()->get('administrador');
        return 'Esto es la edicion, bienvenido: ' . $administrador['name'];
    })->name('detail');
});

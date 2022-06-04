<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\RegisterController;

use App\Http\Controllers\api\ProductController;



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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

// Per peticions fetes des del navegador web
// Sanctum intenta redirigir a una ruta
// anomenada 'login' en cas de no passar
// el token en el header de la petició. Podem afegir el següent codi
// per tractar l'errada en crides http que
// no siguin 'application/json'

Route::get('/login', function () {
    return "Has de validar-te com a usuari!";
})->name("login");


Route::middleware('auth:sanctum')->group( function () {
	Route::resource('products', ProductController::class);
});


Route::get('products/preuInferior/{preu}', [ProductController::class, 'productesPreuInferior'])->name('products.preuInferior');
Route::get('products/delete/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

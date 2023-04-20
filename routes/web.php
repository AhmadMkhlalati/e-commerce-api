<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Hash;
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

Route::get('/login', function () {
    return view('index');
});

Route::middleware(['auth:sanctum'])->get('/{vue_capture?}', function () {
    return view('index');
})->where('vue_capture', '[\/\w\.-]*');

if (config('app.debug')) {
    // for development purposes only -----------------------------------------------------------------

    Route::get('create-token', [TestController::class, 'getToken']);
    Route::get('password', fn() => Hash::make('12345678'));
    Route::get('test', [TestController::class, 'test']);

    // for development purposes only ------------------------------------------------------------

}



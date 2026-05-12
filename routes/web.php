<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
| Swagger UI — OpenAPI fayl repo ildizida (openapi.yaml).
| To‘liq hujjat: {APP_URL}/docs
| Spec URL: {APP_URL}/docs/openapi.yaml
*/
Route::get('/docs/openapi.yaml', function () {
    $path = base_path('openapi.yaml');
    abort_unless(is_file($path), 404);

    return response()->file($path, [
        'Content-Type' => 'application/yaml',
    ]);
})->name('docs.openapi');

Route::view('/docs', 'swagger')->name('docs.swagger');

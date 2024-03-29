<?php

use App\Http\Controllers\CorteDiarioHistoricoController;
use App\Models\corte_diario_historico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('artisan', function () {
    Artisan::call('migrate', [
      '--force' => true
    ]);
    return "ok";
  });
  
Route::get('/corte_diario',[CorteDiarioHistoricoController::class, 'index'])->name('corte_diario');


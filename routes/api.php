<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/loginPegawai', [App\Http\Controllers\Api\AuthController::class, 'loginPegawai']);
Route::post('/loginMember', [App\Http\Controllers\Api\AuthController::class, 'loginMember']);
Route::post('/loginInstruktur', [App\Http\Controllers\Api\AuthController::class, 'loginInstruktur']);
// Route::middleware('auth:api')->post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);

Route::group(['middleware' => 'auth:PegawaiPage'], function(){
    Route::apiResource('/member', App\Http\Controllers\Api\MemberController::class);
    Route::put('/member/resetPassword/{id}', [App\Http\Controllers\Api\MemberController::class, 'resetPassword']);
    Route::apiResource("/instruktur", App\Http\Controllers\Api\InstrukturController::class);
    Route::apiResource("/jadwal", App\Http\Controllers\Api\JadwalUmumController::class);
});
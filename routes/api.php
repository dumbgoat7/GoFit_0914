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
Route::post('/loginMO', [App\Http\Controllers\Api\AuthController::class, 'loginMO']);


Route::put('/resetPasswordIns/{username}', [App\Http\Controllers\Api\InstrukturController::class, 'resetPassword']);

Route::put('/resetPasswordMO/{username}', [App\Http\Controllers\Api\PegawaiController::class, 'resetPassword']);
// Route::middleware('auth:api')->post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);

Route::apiResource("/jadwalHarianforall", App\Http\Controllers\Api\JadwalHarianController::class);

Route::group(['middleware' => 'auth:PegawaiPage'], function(){
    Route::apiResource('/member', App\Http\Controllers\Api\MemberController::class);
    Route::put('/member/resetPassword/{id}', [App\Http\Controllers\Api\MemberController::class, 'resetPassword']);
    Route::put('/member/deactiveMember/{id}', [App\Http\Controllers\Api\MemberController::class, 'deactiveMember']);
    Route::get('/showDeactiveMember', [App\Http\Controllers\Api\MemberController::class, 'getDeactiveMember']);
    Route::get('/showActiveMember', [App\Http\Controllers\Api\MemberController::class, 'ActiveMember']);

    Route::apiResource("/instruktur", App\Http\Controllers\Api\InstrukturController::class);
    Route::apiResource("/pegawai", App\Http\Controllers\Api\PegawaiController::class);
    Route::apiResource("/kelas", App\Http\Controllers\Api\KelasController::class);

    Route::apiResource("/jadwal", App\Http\Controllers\Api\JadwalUmumController::class);
    Route::get('/showMonday', [App\Http\Controllers\Api\JadwalUmumController::class, 'showMonday']);
    Route::get('/showTuesday', [App\Http\Controllers\Api\JadwalUmumController::class, 'showTuesday']);
    Route::get('/showWednesday', [App\Http\Controllers\Api\JadwalUmumController::class, 'showWednesday']);
    Route::get('/showThursday', [App\Http\Controllers\Api\JadwalUmumController::class, 'showThursday']);
    Route::get('/showFriday', [App\Http\Controllers\Api\JadwalUmumController::class, 'showFriday']);
    Route::get('/showSaturday', [App\Http\Controllers\Api\JadwalUmumController::class, 'showSaturday']);
    Route::get('/showSunday', [App\Http\Controllers\Api\JadwalUmumController::class, 'showSunday']);
    
    Route::apiResource("/jadwalHarian", App\Http\Controllers\Api\JadwalHarianController::class);
    Route::get('/showMondayHarian', [App\Http\Controllers\Api\JadwalHarianController::class, 'jadwalHarianMonday']);
    Route::get('/showTuesdayHarian', [App\Http\Controllers\Api\JadwalHarianController::class, 'jadwalHarianTuesday']);
    Route::get('/showWednesdayHarian', [App\Http\Controllers\Api\JadwalHarianController::class, 'jadwalHarianWednesday']);
    Route::get('/showThursdayHarian', [App\Http\Controllers\Api\JadwalHarianController::class, 'jadwalHarianThursday']);
    Route::get('/showFridayHarian', [App\Http\Controllers\Api\JadwalHarianController::class, 'jadwalHarianFriday']);
    Route::get('/showSaturdayHarian', [App\Http\Controllers\Api\JadwalHarianController::class, 'jadwalHarianSaturday']);
    Route::get('/showSundayHarian', [App\Http\Controllers\Api\JadwalHarianController::class, 'jadwalHarianSunday']);
    Route::get('/search/{class}', [App\Http\Controllers\Api\JadwalHarianController::class, 'search']);
    Route::post('/generateJadwal', [App\Http\Controllers\Api\JadwalHarianController::class, 'generateJadwal']);
    Route::get('/showbyDay/{day}', [App\Http\Controllers\Api\JadwalHarianController::class, 'showbyDay']);
    
    Route::apiResource("/depositReguler", App\Http\Controllers\Api\DepositRegulerController::class);
    Route::apiResource("/depositKelas", App\Http\Controllers\Api\DepositKelasController::class);
    Route::put('/resetDeposit/{id}', [App\Http\Controllers\Api\DepositKelasController::class, 'resetDeposit']);
    Route::get('/showExpired', [App\Http\Controllers\Api\DepositKelasController::class, 'showExpired']);
    Route::apiResource("/aktivasi", App\Http\Controllers\Api\TransaksiAktivasiController::class);
    Route::apiResource("/promo", App\Http\Controllers\Api\PromoController::class);
    Route::apiResource("/ijin", App\Http\Controllers\Api\IjinController::class);
    Route::put('/ijin/isConfirmed/{id}', [App\Http\Controllers\Api\IjinController::class, 'isConfirmed']);

    Route::apiResource("/bookingKelas", App\Http\Controllers\Api\bookingKelasController::class);
    Route::get('/getDatabookingKelas/{id}',[App\Http\Controllers\Api\bookingKelasController::class, 'getDatabookingKelas']);
    Route::get('/getDatabookingKelasPaket/{id}',[App\Http\Controllers\Api\bookingKelasController::class, 'getDatabookingKelasPaket']);
    
    Route::apiResource("/bookingGymPegawai", App\Http\Controllers\Api\bookingGymController::class);
    Route::put('/presensiGym/{id}', [App\Http\Controllers\Api\bookingGymController::class, 'presensiGym']);

    Route::apiResource("/detailsBooking", App\Http\Controllers\Api\detailsBookingController::class);

    Route::apiResource("/presensiInstruktur", App\Http\Controllers\Api\presensiInstrukturController::class);
    Route::get('/showActivityMember/{id}', [App\Http\Controllers\Api\MemberController::class, 'showActivityMember']);

    Route::get('/laporanGym/{bulan}', [App\Http\Controllers\Api\LaporanController::class, 'laporanGym']);
    Route::get('/laporanKelas/{bulan}', [App\Http\Controllers\Api\LaporanController::class, 'laporanKelas']);
    Route::get('/laporanPendapatan/{tahun}', [App\Http\Controllers\Api\LaporanController::class, 'laporanPendapatan']);
    ROute::get('/laporanKinerja/{bulan}', [App\Http\Controllers\Api\LaporanController::class, 'laporanKinerja']);
});

Route::group(['middleware' => 'auth:MemberPage'], function(){
    Route::apiResource('/membermobile', App\Http\Controllers\Api\MemberController::class);
    
    Route::apiResource("/bookingGym", App\Http\Controllers\Api\bookingGymController::class);
    Route::get('/showbookingbyMember/{id}', [App\Http\Controllers\Api\bookingGymController::class, 'showbyMember']);
    Route::put('/cancelBooking/{id}', [App\Http\Controllers\Api\bookingGymController::class, 'cancelBooking']);

    Route::apiResource("/detailsBooking", App\Http\Controllers\Api\detailsBookingController::class);

    Route::get('/showDepositRegulerMember/{id}', [App\Http\Controllers\Api\DepositRegulerController::class, 'showDepositRegulerMember']);

    Route::get('/showActiveDepositKelasMember/{id}', [App\Http\Controllers\Api\DepositKelasController::class, 'showActiveDepositKelasMember']);
    Route::get('/showDepositKelasMember/{id}', [App\Http\Controllers\Api\DepositKelasController::class, 'showDepositKelasMember']);
    Route::get('/showActivationMember/{id}', [App\Http\Controllers\Api\TransaksiAktivasiController::class, 'showActivationMember']);
    
    Route::get('/showBookingKelasMember/{id}', [App\Http\Controllers\Api\bookingKelasController::class, 'showBookingKelasMember']);
    
    Route::get('/showBookingbyMember/{id}', [App\Http\Controllers\Api\bookingGymController::class, 'showbyMember']);

    Route::apiResource("/bookingKelasMobile", App\Http\Controllers\Api\bookingKelasController::class);
});

Route::group(['middleware' => 'auth:InstrukturPage'], function(){
    Route::apiResource('/instrukturMobile', App\Http\Controllers\Api\InstrukturController::class);

    Route::apiResource("/ijinInstruktur", App\Http\Controllers\Api\IjinController::class);
    Route::get('/showByInstruktur/{id}', [App\Http\Controllers\Api\IjinController::class, 'showByInstruktur']);
    Route::put('/presensi/{id}', [App\Http\Controllers\Api\bookingKelasController::class, 'presensi']);

});
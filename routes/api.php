<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\GateController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\KoordinatController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\Mobile\AbsensiController;
use App\Http\Controllers\Mobile\PegawaiProfileController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::prefix('w1/user')->controller(UserController::class)->group(function () {
	Route::get    ('/'     , 'all'  );
	Route::get    ('/{id}' , 'getById');
	Route::post   ('/'     , 'store'  );
	Route::put   ('/{id}' , 'update' );
	Route::delete ('/{id}' , 'delete' );
});

Route::prefix('w1/departement')->controller(DepartementController::class)->group(function () {
	Route::get    ('/'     , 'all'  );
	Route::get    ('/{id}' , 'getById');
	Route::post   ('/'     , 'store'  );
	Route::put   ('/{id}' , 'update' );
	Route::delete ('/{id}' , 'delete' );
});

Route::prefix('w1/jabatan')->controller(JabatanController::class)->group(function () {
	Route::get    ('/'     , 'all'  );
	Route::get    ('/{id}' , 'getById');
	Route::post   ('/'     , 'store'  );
	Route::put    ('/{id}' , 'update' );
	Route::delete ('/{id}' , 'delete' );
});

Route::prefix('w1/koordinat')->controller(KoordinatController::class)->group(function () {
	Route::get    ('/'     , 'all'  );
	Route::get    ('/{id}' , 'getById');
	Route::post   ('/'     , 'store'  );
	Route::put   ('/{id}' , 'update' );
	Route::delete ('/{id}' , 'delete' );
});

Route::prefix('w1/setup')->controller(SetupController::class)->group(function () {
	Route::get    ('/'     , 'all'  );
	Route::get    ('/{id}' , 'getById');
	Route::post   ('/'     , 'store'  );
	Route::put   ('/{id}' , 'update' );
	Route::delete ('/{id}' , 'delete' );
});

Route::prefix('w1/gate')->controller(GateController::class)->group(function () {
	Route::get    ('/'     , 'all'  );
	Route::get    ('/{id}' , 'getById');
	Route::post   ('/'     , 'store'  );
	Route::put   ('/{id}' , 'update' );
	Route::delete ('/{id}' , 'delete' );
});

Route::prefix('w1/pegawai')->controller(PegawaiController::class)->group(function () {
	Route::get    ('/'     , 'all'  );
	Route::get    ('/{id}' , 'getById');
	Route::post   ('/'     , 'store'  );
	Route::put   ('/{id}' , 'update' );
	Route::delete ('/{id}' , 'delete' );
});

Route::prefix('w1/log')->controller(LogController::class)->group(function () {
	Route::get    ('/'     , 'all'  );
	Route::get    ('/{id}' , 'getById');
	Route::post   ('/'     , 'store'  );
	Route::put   ('/{id}' , 'update' );
	Route::delete ('/{id}' , 'delete' );
});

Route::prefix('w1/kehadiran')->controller(KehadiranController::class)->group(function () {
	Route::get    ('/'     , 'all'  );
	Route::get    ('/{id}' , 'getById');
	Route::post   ('/'     , 'store'  );
	Route::put   ('/{id}' , 'update' );
	Route::delete ('/{id}' , 'delete' );
});

Route::prefix('mob/profile')->middleware('auth:api')->group(function () {
	Route::get('/', [PegawaiProfileController::class, 'getCurrentUserData']);
	Route::get('/kehadiran', [PegawaiProfileController::class, 'getCurrnetUserKehadiran']);
});

Route::prefix('mob/absensi')->middleware('auth:api')->group(function () {
	Route::post('/save', [AbsensiController::class, 'presentPegawai']);
	Route::get('/history', [AbsensiController::class,'getCurrentPresentHistory']);
});
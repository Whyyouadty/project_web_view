<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\GateController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\KoordinatController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\UserController;
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

Route::get('/'           , [DashboardController  ::class, 'index' ])->middleware(['auth'                          ])->name('pages.dashboard'  );
Route::get('/user'       , [UserController       ::class, 'index' ])->middleware(['auth', 'role:super-admin|admin'])->name('pages.user'       );
Route::get('/departement', [DepartementController::class, 'index' ])->middleware(['auth', 'role:super-admin|admin'])->name('pages.departement');
Route::get('/jabatan'    , [JabatanController    ::class, 'index' ])->middleware(['auth', 'role:super-admin|admin'])->name('pages.jabatan'    );
Route::get('/koordinat'  , [KoordinatController  ::class, 'index' ])->middleware(['auth', 'role:super-admin|admin'])->name('pages.koordinat'  );
Route::get('/setup'      , [SetupController      ::class, 'index' ])->middleware(['auth', 'role:super-admin|admin'])->name('pages.setup'      );
Route::get('/gate'       , [GateController       ::class, 'index' ])->middleware(['auth', 'role:super-admin|admin'])->name('pages.gate'       );
Route::get('/pegawai'    , [PegawaiController    ::class, 'index' ])->middleware(['auth', 'role:super-admin|admin'])->name('pages.pegawai'    );
Route::get('/log'        , [LogController        ::class, 'index' ])->middleware(['auth', 'role:super-admin|admin'])->name('pages.log'        );
Route::get('/kehadiran'  , [KehadiranController  ::class, 'index' ])->middleware(['auth', 'role:super-admin|admin'])->name('pages.kehadiran'  );

Route::get ('/loginview'    , [AuthController ::class, 'index' ])->name('login'         );
Route::post('/loginprocess' , [AuthController ::class, 'login' ])->name('login-process' );
Route::get('/logout'       , [AuthController ::class, 'logout' ])->middleware(['auth'])->name('logout');
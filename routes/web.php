<?php

use App\Http\Controllers\PSOController;
use Illuminate\Support\Facades\Route;
// use App\Http\Middleware\AuthenticateFile;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;

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

Auth::routes();

// Route::get('/login', function () {
//     return view('login');
// })->middleware('guest');

Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout']);

// Route::post('/login', function () {
//     return view('index');
// })->name('login.submit')->middleware(AuthenticateFile::class);

Route::group(['middleware' => 'ceksesi'], function () {

    Route::get('/', function () {
        return view('index');
    });

    Route::get('/data', function () {
        return view('data');
    })->name('data');

    Route::get('/pso', [PSOController::class, 'destroy'])->name('pso.hapus');

    Route::post('import', [PSOController::class, 'import'])->name('pso.import');
    Route::post('add', [PSOController::class, 'addDataBaru'])->name('pso.add');
    Route::get('/hitung', [PSOController::class, 'hitung'])->name('pso.hitung');

    // Route::get('/read', [PSOController::class, 'readText'])->name('pso.read');
});
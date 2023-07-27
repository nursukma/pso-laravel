<?php

use App\Http\Controllers\ActivityLog;
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

// Auth::routes();

// Route::get('/login', function () {
//     return view('login');
// })->middleware('guest');

Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('ceklogin');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout']);

// Route::post('/login', function () {
//     return view('index');
// })->name('login.submit')->middleware(AuthenticateFile::class);

Route::group(['middleware' => ['revalidate', 'ceksesi','lang']], function () {
    // Route::group(['middleware' => ['ceksesi', 'revalidate']], function () {

    Route::resource('activity-log', ActivityLog::class);

    Route::get('/', function () {
        return view('dashboard');
    });

    Route::get('/form-data', function () {
        return view('form-data.index');
    });

    Route::get('/table-data', function () {
        if (session()->has('data')) {
            $dataCount = count(session()->get('data')[0]);

            if ($dataCount < 0) {
                session()->forget('data');
                // return redirect('/')->with('error', 'maaf template tidak cocok!');
                // } else {
                //     return view('table-data.index');
            }
        } else {
            return view('table-data.index');
        }
        return view('table-data.index');
    })->name('data');

    Route::delete('/pso', [PSOController::class, 'destroy'])->name('pso.hapus');

    Route::post('import', [PSOController::class, 'import'])->name('pso.import');

    Route::post('import-asli', [PSOController::class, 'import_asli'])->name('pso.import_asli');

    Route::post('/hitung', [PSOController::class, 'hitung'])->name('pso.hitung');

    Route::get('/search-data/{id}', [PSOController::class, 'searchData'])->name('pso.search');

    Route::put('/update-data/{id}', [PSOController::class, 'updateData'])->name('pso.updateData');

    Route::delete('/delete-data/{id}', [PSOController::class, 'deleteData'])->name('pso.deleteData');

    Route::post('view-pdf', [PSOController::class, 'viewPDF'])->name('view-pdf');

    Route::get('contoh-excel', [PSOController::class, 'downloadPdfFile'])->name('download-contoh');

    Route::get('/variabel', [PSOController::class, 'indexVariabel'])->name('variabel.index');

    Route::get('/getVariabel', [PSOController::class, 'getVariabel'])->name('variabel.getData');

    Route::post('add-variabel', [PSOController::class, 'addVariabel'])->name('variabel.add');

    Route::post('lang', [PSOController::class, 'lang'])->name('c_lang');
});
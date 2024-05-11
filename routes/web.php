<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller; 


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Routes pour les utilisateurs authentifiés
Route::group(['middleware' => 'auth'], function () {
// Définir la route pour l'action d'ajout

    Route::post('/ajouter', [Controller::class, 'ajouter'])->name('ajouter');

    // Routes pour les pays
    Route::resource('pays', 'PaysController');
    
    // Routes pour les villes
    Route::resource('villes', 'VilleController');
    
    // Routes pour les personnes
    Route::resource('personnes', 'PersonneController');

    // Export Data
    Route::get('/export-csv', 'Controller@exportCsv')->name('export.csv');
    Route::get('/export-excel', 'Controller@exportExcel')->name('export.excel');

});
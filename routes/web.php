<?php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ExportController::class, 'index'])->name('home');
Route::get('/tables', [ExportController::class, 'tables'])->name('export.tables');
Route::get('/columns/{table}', [ExportController::class, 'columns'])->name('export.columns');
Route::post('/export', [ExportController::class, 'export'])->name('export.create');
Route::get('/download/{id}', [ExportController::class, 'download'])->name('export.download');

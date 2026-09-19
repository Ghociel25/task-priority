<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// Mengarahkan halaman utama (root '/') ke fungsi index di TaskController
Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

// Route untuk memproses form tambah tugas
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

// Route untuk mengubah status tugas
Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

// Route untuk menghapus tugas
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

//Route untuk menandai tugas sebagai selesai
Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
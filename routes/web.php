<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Str; 

// untuk mengarahkan ke halaman utama dengan token acak
Route::get('/', function () {
    return redirect('/' . Str::random(16));
});

// Route untuk memproses form tambah tugas (biarkan)
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

// Route untuk mengubah status tugas (biarkan)
Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

// Route untuk menghapus tugas (biarkan)
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

// Route untuk menandai tugas sebagai selesai (biarkan)
Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');

// Route utama dengan token (punya lu sudah ada, biarkan)
Route::get('/{token}', [TaskController::class, 'index'])->name('tasks.index.withToken');
Route::post('/{token}', [TaskController::class, 'store'])->name('tasks.store.withToken');
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\TaskController;


// આ ચાર રૂટ હોવા જરૂરી છે
Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');

// આ લાઈન કદાચ મિસિંગ હશે, તે ઉમેરી દો:
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
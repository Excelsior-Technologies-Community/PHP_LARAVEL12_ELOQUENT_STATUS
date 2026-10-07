<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Task Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/', [TaskController::class, 'index'])
    ->name('tasks.index');


/*
|--------------------------------------------------------------------------
| Create Task
|--------------------------------------------------------------------------
*/

Route::post('/tasks', [TaskController::class, 'store'])
    ->name('tasks.store');


/*
|--------------------------------------------------------------------------
| Update Task
|--------------------------------------------------------------------------
*/

Route::patch('/tasks/{task}', [TaskController::class, 'update'])
    ->name('tasks.update');


/*
|--------------------------------------------------------------------------
| Duplicate Task
|--------------------------------------------------------------------------
*/

Route::post('/tasks/{task}/duplicate', [TaskController::class, 'duplicate'])
    ->name('tasks.duplicate');


/*
|--------------------------------------------------------------------------
| Delete Task
|--------------------------------------------------------------------------
*/

Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
    ->name('tasks.destroy');


/*
|--------------------------------------------------------------------------
| Bulk Actions
|--------------------------------------------------------------------------
*/

Route::post('/tasks/bulk-action', [TaskController::class, 'bulkAction'])
    ->name('tasks.bulk');


/*
|--------------------------------------------------------------------------
| CSV Export
|--------------------------------------------------------------------------
*/

Route::get('/tasks/export/csv', [TaskController::class, 'export'])
    ->name('tasks.export');
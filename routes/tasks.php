<?php

use App\Http\Controllers\Admin\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Task assignment
|--------------------------------------------------------------------------
| Included from routes/web.php INSIDE the existing
| Route::middleware(['auth','admin'])->prefix('admin')->name('admin.') group,
| so every name below becomes admin.tasks.* and every URL /admin/tasks/*.
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('tasks', [TaskController::class, 'index'])
            ->middleware('permission:tasks.view')
            ->name('tasks.index');

        Route::get('tasks/create', [TaskController::class, 'create'])
            ->middleware('permission:tasks.create')
            ->name('tasks.create');

        Route::post('tasks', [TaskController::class, 'store'])
            ->middleware('permission:tasks.create')
            ->name('tasks.store');

        Route::get('tasks/department-employees/{department}', [TaskController::class, 'departmentEmployees'])
            ->middleware('permission:tasks.create')
            ->whereNumber('department')
            ->name('tasks.department-employees');

        Route::get('tasks/{task}', [TaskController::class, 'show'])
            ->middleware('permission:tasks.view')
            ->name('tasks.show');

        Route::post('tasks/{task}/status', [TaskController::class, 'updateStatus'])
            ->middleware('permission:tasks.update-status')
            ->name('tasks.status');

        Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])
            ->middleware('permission:tasks.edit')
            ->name('tasks.edit');

        Route::put('tasks/{task}', [TaskController::class, 'update'])
            ->middleware('permission:tasks.edit')
            ->name('tasks.update');

        Route::post('tasks/{task}/cancel', [TaskController::class, 'cancel'])
            ->middleware('permission:tasks.edit')
            ->name('tasks.cancel');

        Route::delete('tasks/{task}', [TaskController::class, 'destroy'])
            ->middleware('permission:tasks.delete')
            ->name('tasks.destroy');
    });

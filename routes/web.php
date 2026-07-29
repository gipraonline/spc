
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\PermissionController;


Route::get('/', function () {
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['verified', 'permission:dashboard.view'])
        ->name('dashboard');


    Route::get('/dashboard-test', [DashboardController::class, 'test'])
        ->middleware('verified')
        ->name('dashboard.test');


    Route::get('/view-report', [DashboardController::class, 'viewSalesReport'])
        ->middleware('verified')
        ->name('view.report');


    Route::get('/view-store-report', [DashboardController::class, 'viewStoreReport'])
        ->middleware('verified')
        ->name('view.store.report');


    Route::put('/change-password', [ProfileController::class, 'updatePassword'])
        ->name('password.update');


    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');


    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

});



/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Designations
    |--------------------------------------------------------------------------
    */

    Route::get('designations', [DesignationController::class, 'index'])
        ->middleware('permission:designations.view')
        ->name('designations.index');


    Route::get('designations/create', [DesignationController::class, 'create'])
        ->middleware('permission:designations.create')
        ->name('designations.create');


    Route::post('designations/store', [DesignationController::class, 'store'])
        ->middleware('permission:designations.create')
        ->name('designations.store');



    /*
    |--------------------------------------------------------------------------
    | Employees
    |--------------------------------------------------------------------------
    */

    Route::get('employees', [EmployeeController::class, 'index'])
        ->middleware('permission:employees.view')
        ->name('employees.index');


    Route::get('employees/create', [EmployeeController::class, 'create'])
        ->middleware('permission:employees.create')
        ->name('employees.create');


    Route::post('employees', [EmployeeController::class, 'store'])
        ->middleware('permission:employees.create')
        ->name('employees.store');


    Route::get('employees/{employee}/edit', [EmployeeController::class, 'edit'])
        ->middleware('permission:employees.edit')
        ->name('employees.edit');


    Route::put('employees/{employee}', [EmployeeController::class, 'update'])
        ->middleware('permission:employees.edit')
        ->name('employees.update');


    Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])
        ->middleware('permission:employees.delete')
        ->name('employees.destroy');



    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */

    Route::get('products', [ProductController::class, 'index'])
        ->middleware('permission:products.view')
        ->name('products.index');


    Route::get('products/create', [ProductController::class, 'create'])
        ->middleware('permission:products.create')
        ->name('products.create');


    Route::post('products', [ProductController::class, 'store'])
        ->middleware('permission:products.create')
        ->name('products.store');


    Route::get('products/{product}', [ProductController::class, 'show'])
        ->middleware('permission:products.view')
        ->name('products.show');


    Route::get('products/{product}/edit', [ProductController::class, 'edit'])
        ->middleware('permission:products.edit')
        ->name('products.edit');


    Route::put('products/{product}', [ProductController::class, 'update'])
        ->middleware('permission:products.edit')
        ->name('products.update');


    Route::delete('products/{product}', [ProductController::class, 'destroy'])
        ->middleware('permission:products.delete')
        ->name('products.destroy');


    Route::get('products/export', [ProductController::class, 'export'])
        ->middleware('permission:products.export')
        ->name('products.export');


    Route::get('check-product-code', [ProductController::class, 'checkCode'])
        ->middleware('permission:products.create|products.edit')
        ->name('check.product.code');



    /*
    |--------------------------------------------------------------------------
    | Sales Orders
    |--------------------------------------------------------------------------
    */

    Route::get('salesorders', [SalesController::class, 'index'])
        ->middleware('permission:sales-orders.view')
        ->name('salesorders.index');


    Route::get('salesorders/create', [SalesController::class, 'create'])
        ->middleware('permission:sales-orders.create')
        ->name('salesorders.create');


    Route::post('salesorders', [SalesController::class, 'store'])
        ->middleware('permission:salesorders.add-sale')
        ->name('salesorders.store');


    Route::get('salesorders/{salesorder}', [SalesController::class, 'show'])
        ->middleware('permission:salesorders.view-details')
        ->name('salesorders.show');


    Route::get('salesorders/{salesorder}/edit', [SalesController::class, 'edit'])
        ->middleware('permission:salesorders.edit')
        ->name('salesorders.edit');


    Route::put('salesorders/{salesorder}', [SalesController::class, 'update'])
        ->middleware('permission:salesorders.edit')
        ->name('salesorders.update');


    Route::delete('salesorders/{salesorder}', [SalesController::class, 'destroy'])
        ->middleware('permission:salesorders.delete')
        ->name('salesorders.destroy');



    /*
    |--------------------------------------------------------------------------
    | RBAC Management
    |--------------------------------------------------------------------------
    */

    Route::resource('menus', MenuController::class)
        ->middleware('permission:menu-management.view');


    Route::resource('roles', RoleController::class)
        ->middleware('permission:role-management.view');


    Route::resource('users', AdminUserController::class)
        ->middleware('permission:user-management.view');


    Route::resource('permissions', PermissionController::class)
        ->middleware('permission:permission-management.view');

});



require __DIR__.'/auth.php';



/*
|--------------------------------------------------------------------------
| Sales Reports
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::match(['get','post'], '/sales-report', [SalesController::class, 'salesReport'])
        ->middleware('permission:verified-sales.view')
        ->name('sales.report');


    Route::get('/export-sales-report', [SalesController::class, 'exportSalesReport'])
        ->middleware('permission:verified-sales.export')
        ->name('export.sales.report');

});

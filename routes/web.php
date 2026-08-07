<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\LeadsController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\FieldLogController;
use App\Http\Controllers\Admin\SalesController;


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

    Route::post('employees/search', [EmployeeController::class, 'search'])
        ->middleware('permission:employees.view')
        ->name('employees.search');

    Route::get('employees/clear-search', [EmployeeController::class, 'clearSearch'])
        ->middleware('permission:employees.view')
        ->name('employees.clearSearch');

    Route::get('/employees/reporting-managers/{designation}', [EmployeeController::class, 'getReportingManagers']);


    /*
    |--------------------------------------------------------------------------
    | Stores
    |--------------------------------------------------------------------------
    */



     Route::get('franchises', [StoreController::class, 'index'])
    ->middleware('permission:franchises.view')
    ->name('franchises.index');

    Route::get('franchises/create', [StoreController::class, 'create'])
        ->middleware('permission:franchises.create')
        ->name('franchises.create');

    Route::post('franchises', [StoreController::class, 'store'])
        ->middleware('permission:franchises.create')
        ->name('franchises.store');

    Route::get('franchises/{franchise}/edit', [StoreController::class, 'edit'])
        ->middleware('permission:franchises.edit')
        ->name('franchises.edit');

    Route::put('franchises/{franchise}', [StoreController::class, 'update'])
        ->middleware('permission:franchises.edit')
        ->name('franchises.update');

    Route::delete('franchises/{franchise}', [StoreController::class, 'destroy'])
        ->middleware('permission:franchises.delete')
        ->name('franchises.destroy');

    Route::post('franchises/search', [StoreController::class, 'search'])
        ->middleware('permission:franchises.view')
        ->name('franchises.search');

    Route::get('franchises/clear-search', [StoreController::class, 'clearSearch'])
        ->middleware('permission:franchises.view')
        ->name('franchises.clearSearch');
    // *********************************************

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


    // Route::get('products/{product}', [ProductController::class, 'show'])
    //     ->middleware('permission:products.view')
    //     ->name('products.show');


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

    Route::post('products/search', [ProductController::class, 'search'])
        ->middleware('permission:products.view')
        ->name('products.search');

    Route::get('products/clear-search', [ProductController::class, 'clearSearch'])
        ->middleware('permission:products.view')
        ->name('products.clearSearch');


    /*
    |--------------------------------------------------------------------------
    | District Filter
    |--------------------------------------------------------------------------
    */
        Route::get('districts', [SalesController::class, 'districtFilter'])
        ->name('filterDistrict');

    /*
    |--------------------------------------------------------------------------
    | Leads
    |--------------------------------------------------------------------------
    */

    Route::get('leads', [LeadsController::class, 'index'])
        ->middleware('permission:leads.view')
        ->name('leads.index');


    Route::get('leads/create', [LeadsController::class, 'create'])
        ->middleware('permission:leads.add-lead')
        ->name('leads.create');


    Route::post('leads', [LeadsController::class, 'store'])
        ->middleware('permission:leads.create')
        ->name('leads.store');


    Route::get('leads/show/{id}', [LeadsController::class, 'show'])
        ->middleware('permission:leads.view-details')
        ->name('leads.show');

    Route::put('leads/approval', [LeadsController::class, 'approve'])
    ->middleware('permission:leads.approval')
    ->name('leads.approval.save');

    Route::put('leads/followup', [LeadsController::class, 'followupSave'])
    ->middleware('permission:leads.follow-up')
    ->name('leads.followup.store');



    Route::get('leads/edit/{id}', [LeadsController::class, 'edit'])
        ->middleware('permission:leads.edit')
        ->name('leads.edit');


    Route::put('leads/update', [LeadsController::class, 'update'])
        ->middleware('permission:leads.edit')
        ->name('leads.update');


    Route::delete('leads/delete/{id}', [LeadsController::class, 'destroy'])
        ->middleware('permission:leads.delete')
        ->name('leads.destroy');


    Route::post('leads/existingCustomer', [LeadsController::class, 'existingCustomer'])
        ->name('leads.existingCustomer');


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
        ->middleware('permission:sales-orders.create')
        ->name('salesorders.store');


    Route::get('salesorders/show/{id}', [SalesController::class, 'show'])
        ->middleware('permission:sales-orders.view-details')
        ->name('salesorders.show');

    Route::put('salesorders/approval', [SalesController::class, 'approve'])
    ->middleware('permission:sales-orders.approval')
    ->name('salesorders.approval.save');

    Route::put('salesorders/followup', [SalesController::class, 'followupSave'])
    ->middleware('permission:sales-orders.follow-up')
    ->name('salesorders.followup.store');



    Route::get('salesorders/edit/{id}', [SalesController::class, 'edit'])
        ->middleware('permission:sales-orders.edit')
        ->name('salesorders.edit');


    Route::put('salesorders/update', [SalesController::class, 'update'])
        ->middleware('permission:sales-orders.edit')
        ->name('salesorders.update');


    Route::delete('salesorders/delete/{id}', [SalesController::class, 'destroy'])
        ->middleware('permission:sales-orders.delete')
        ->name('salesorders.destroy');

    Route::get('filterDistrict', [SalesController::class, 'franchiseFilter'])
        ->name('salesorders.filterDistrict');

    Route::get('filter-franchise', [SalesController::class, 'franchiseFilter'])
        ->name('admin.filterFranchise');



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

    /*
    |--------------------------------------------------------------------------
    | Customers
    |--------------------------------------------------------------------------
    */

    Route::get('customers', [CustomerController::class, 'index'])
        ->middleware('permission:customers.view')
        ->name('customers.index');

    Route::get('customers/create', [CustomerController::class, 'create'])
        ->middleware('permission:customers.create')
        ->name('customers.create');

    Route::post('customers', [CustomerController::class, 'store'])
        ->middleware('permission:customers.create')
        ->name('customers.store');

    Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])
        ->middleware('permission:customers.edit')
        ->name('customers.edit');

    Route::put('customers/{customer}', [CustomerController::class, 'update'])
        ->middleware('permission:customers.edit')
        ->name('customers.update');

    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])
        ->middleware('permission:customers.delete')
        ->name('customers.destroy');

    Route::post('customers/search', [CustomerController::class, 'search'])
        ->middleware('permission:customers.view')
        ->name('customers.search');

    Route::get('customers/clear-search', [CustomerController::class, 'clearSearch'])
        ->middleware('permission:customers.view')
        ->name('customers.clearSearch');

    Route::get('districts/{state}', [CustomerController::class, 'getDistricts'])
        ->name('admin.districts');


     /*
    |--------------------------------------------------------------------------
    | Field log
    |--------------------------------------------------------------------------
    */

    Route::get('/field-log', [FieldLogController::class, 'index'])
        ->middleware('permission:field-log.view')
        ->name('field-log.index');

    Route::post('/field-log/check-in', [FieldLogController::class, 'checkIn'])
    ->middleware('permission:field-log.check-in')
        ->name('field-log.checkin');

    Route::post('/field-log/task', [FieldLogController::class, 'storeTask'])
        ->name('field-log.task.store');

    Route::post('/field-log/task/{task}/update', [FieldLogController::class, 'updateTask'])
        ->name('field-log.task.update');

    Route::post('/field-log/check-out', [FieldLogController::class, 'checkOut'])
        ->name('field-log.checkout');




});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Sales Reports
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::match(['get','post'], '/sales-report', [LeadsController::class, 'salesReport'])
        ->middleware('permission:verified-sales.view')
        ->name('sales.report');


    Route::get('/export-sales-report', [LeadsController::class, 'exportSalesReport'])
        ->middleware('permission:verified-sales.export')
        ->name('export.sales.report');

});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserRoleController;

/*
|--------------------------------------------------------------------------
| Public / Auth
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('Auth.login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/admin', [AdminController::class, 'index'])
    ->middleware('auth')
    ->name('admin.entry');

Route::prefix('admin')
    ->middleware(['auth', 'permission:access_admin_panel'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard', [DashboardController::class, 'admin'])
            ->middleware('permission:view_dashboard')
            ->name('admin.dashboard');

        /*
        |--------------------------------------------------------------------------
        | Banners
        |--------------------------------------------------------------------------
        */
        Route::get('/banners', [BannerController::class, 'index'])
            ->middleware('permission:view_banners')
            ->name('banners.index');

        Route::post('/banners', [BannerController::class, 'store'])
            ->middleware('permission:create_banners')
            ->name('banners.store');

        Route::put('/banners/{banner}', [BannerController::class, 'update'])
            ->middleware('permission:edit_banners')
            ->name('banners.update');

        Route::delete('/banners/{banner}', [BannerController::class, 'destroy'])
            ->middleware('permission:delete_banners')
            ->name('banners.destroy');

        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */
        Route::get('/products', [ProductController::class, 'index'])
            ->middleware('permission:view_products')
            ->name('products.index');

        Route::post('/products', [ProductController::class, 'store'])
            ->middleware('permission:create_products')
            ->name('products.store');

        Route::put('/products/{product}', [ProductController::class, 'update'])
            ->middleware('permission:edit_products')
            ->name('products.update');

        Route::delete('/products/{product}', [ProductController::class, 'destroy'])
            ->middleware('permission:delete_products')
            ->name('products.destroy');

        Route::get('/products/export/csv', [ProductController::class, 'exportCSV'])
            ->middleware('permission:view_products')
            ->name('products.export.csv');

        Route::get('/products/export/pdf', [ProductController::class, 'exportPDF'])
            ->middleware('permission:view_products')
            ->name('products.export.pdf');

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */
        Route::get('/category', [CategoryController::class, 'index'])
            ->middleware('permission:view_categories')
            ->name('categories.index');

        Route::post('/category/store', [CategoryController::class, 'store'])
            ->middleware('permission:create_categories')
            ->name('categories.store');

        Route::put('/category/{category}', [CategoryController::class, 'update'])
            ->middleware('permission:edit_categories')
            ->name('categories.update');

        Route::delete('/category/{category}', [CategoryController::class, 'destroy'])
            ->middleware('permission:delete_categories')
            ->name('categories.destroy');

        Route::get('/category/export/csv', [CategoryController::class, 'exportCSV'])
            ->middleware('permission:view_categories')
            ->name('categories.export.csv');

        Route::get('/category/export/pdf', [CategoryController::class, 'exportPDF'])
            ->middleware('permission:view_categories')
            ->name('categories.export.pdf');

        /*
        |--------------------------------------------------------------------------
        | Brands
        |--------------------------------------------------------------------------
        */
        Route::get('/brands', [BrandsController::class, 'index'])
            ->middleware('permission:view_brands')
            ->name('brands.index');

        Route::post('/brands/store', [BrandsController::class, 'store'])
            ->middleware('permission:create_brands')
            ->name('brands.store');

        Route::put('/brand/{brand}', [BrandsController::class, 'update'])
            ->middleware('permission:edit_brands')
            ->name('brands.update');

        Route::delete('/brands/{brand}', [BrandsController::class, 'destroy'])
            ->middleware('permission:delete_brands')
            ->name('brands.destroy');

        Route::get('/brands/export/csv', [BrandsController::class, 'exportCSV'])
            ->middleware('permission:view_brands')
            ->name('brands.export.csv');

        Route::get('/brands/export/pdf', [BrandsController::class, 'exportPDF'])
            ->middleware('permission:view_brands')
            ->name('brands.export.pdf');

        /*
        |--------------------------------------------------------------------------
        | Orders
        |--------------------------------------------------------------------------
        */
        Route::get('/orders', [OrderController::class, 'index'])
            ->middleware('permission:view_orders')
            ->name('orders.index');

        Route::get('/orders/notifications', [OrderController::class, 'notifications'])
            ->middleware('permission:view_orders')
            ->name('orders.notifications');

        Route::post('/orders/{id}/status', [OrderController::class, 'changeStatus'])
            ->middleware('permission:update_orders')
            ->name('orders.change-status');

        Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])
            ->middleware('permission:cancel_orders')
            ->name('orders.cancel');

        Route::get('/orders/{id}', [OrderController::class, 'show'])
            ->middleware('permission:view_orders')
            ->name('orders.show');

        Route::get('/orders/export/csv', [OrderController::class, 'exportCSV'])
            ->middleware('permission:view_orders')
            ->name('orders.export.csv');

        Route::get('/orders/export/pdf', [OrderController::class, 'exportPDF'])
            ->middleware('permission:view_orders')
            ->name('orders.export.pdf');

        Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice'])
            ->middleware('permission:view_orders')
            ->name('admin.orders.invoice');

        Route::get('/orders/{id}/invoice/pdf', [OrderController::class, 'invoicePdf'])
            ->middleware('permission:view_orders')
            ->name('admin.orders.invoice.pdf');

        /*
        |--------------------------------------------------------------------------
        | Customers
        |--------------------------------------------------------------------------
        |
        | បច្ចុប្បន្ន seeder permission list អ្នកមានតែ:
        | - view_customers
        | - delete_customers
        |
        | ដូច្នេះ store/update ខ្ញុំ map ទៅ view_customers សិន
        | បើចង់ clean ជាងនេះ អ្នកគួរបន្ថែម:
        | - create_customers
        | - edit_customers
        |--------------------------------------------------------------------------
        */
        Route::get('/customers', [CustomersController::class, 'customers'])
            ->middleware('permission:view_customers')
            ->name('customers.index');

        Route::get('/customers/export/csv', [CustomersController::class, 'exportCustomersCSV'])
            ->middleware('permission:view_customers')
            ->name('customers.export.csv');

        Route::get('/customers/export/pdf', [CustomersController::class, 'exportCustomersPDF'])
            ->middleware('permission:view_customers')
            ->name('customers.export.pdf');

        /*
        |--------------------------------------------------------------------------
        | Promotions
        |--------------------------------------------------------------------------
        */
        Route::get('/promotions', [PromotionController::class, 'index'])
            ->middleware('permission:view_promotions')
            ->name('promotions.index');

        Route::post('/promotions', [PromotionController::class, 'store'])
            ->middleware('permission:create_promotions')
            ->name('promotions.store');

        Route::put('/promotions/{promotion}', [PromotionController::class, 'update'])
            ->middleware('permission:edit_promotions')
            ->name('promotions.update');

        Route::delete('/promotions/{promotion}', [PromotionController::class, 'destroy'])
            ->middleware('permission:delete_promotions')
            ->name('promotions.destroy');

        Route::post('/promotions/{promotion}/products', [PromotionController::class, 'attachProducts'])
            ->middleware('permission:edit_promotions')
            ->name('promotions.products.attach');

        /*
        |--------------------------------------------------------------------------
        | Coupons
        |--------------------------------------------------------------------------
        */
        Route::get('/coupons', [CouponController::class, 'index'])
            ->middleware('permission:view_coupons')
            ->name('coupons.index');

        Route::post('/coupons', [CouponController::class, 'store'])
            ->middleware('permission:create_coupons')
            ->name('coupons.store');

        Route::put('/coupons/{coupon}', [CouponController::class, 'update'])
            ->middleware('permission:edit_coupons')
            ->name('coupons.update');

        Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy'])
            ->middleware('permission:delete_coupons')
            ->name('coupons.destroy');

        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */
        Route::get('/reports/dash', [ReportsController::class, 'dashboard'])
            ->middleware('permission:view_reports')
            ->name('reports.dashboard');

        Route::get('/reports/sales', [ReportsController::class, 'sales'])
            ->middleware('permission:view_sales_report')
            ->name('reports.sales');

        Route::get('/reports/sales/export/csv', [ReportsController::class, 'exportSalesCsv'])
            ->middleware('permission:view_sales_report')
            ->name('reports.sales.export.csv');

        Route::get('/reports/sales/export/pdf', [ReportsController::class, 'exportSalesPdf'])
            ->middleware('permission:view_sales_report')
            ->name('reports.sales.export.pdf');

        Route::get('/reports/sales/{date}/details', [ReportsController::class, 'salesDetails'])
            ->middleware('permission:view_sales_report')
            ->name('reports.sales.details');

        Route::get('/reports/sales/{date}/details/export/csv', [ReportsController::class, 'exportSalesDetailsCsv'])
            ->middleware('permission:view_sales_report')
            ->name('reports.sales.details.export.csv');

        Route::get('/reports/sales/{date}/details/export/pdf', [ReportsController::class, 'exportSalesDetailsPdf'])
            ->middleware('permission:view_sales_report')
            ->name('reports.sales.details.export.pdf');

        Route::get('/reports/orders', [ReportsController::class, 'orders'])
            ->middleware('permission:view_reports')
            ->name('reports.orders');

        Route::get('/reports/orders/{order}', [ReportsController::class, 'orderDetails'])
            ->middleware('permission:view_reports')
            ->name('reports.orders.details');

        Route::get('/reports/products', [ReportsController::class, 'products'])
            ->middleware('permission:view_reports')
            ->name('reports.products');

        Route::get('/reports/inventory', [ReportsController::class, 'inventory'])
            ->middleware('permission:view_reports')
            ->name('reports.inventory');

        Route::get('/reports/customers', [ReportsController::class, 'customers'])
            ->middleware('permission:view_customers_report')
            ->name('reports.customers');

        Route::get('/customers/export/csv', [ReportsController::class, 'exportCustomersCsv'])
            ->middleware('permission:view_customers_report')
            ->name('reports.customers.export.csv');

        Route::get('/customers/export/pdf', [ReportsController::class, 'exportCustomersPdf'])
            ->middleware('permission:view_customers_report')
            ->name('reports.customers.export.pdf');

        Route::get('/reports/payments', [ReportsController::class, 'payments'])
            ->middleware('permission:view_reports')
            ->name('reports.payments');

        Route::get('/payments/export/csv', [ReportsController::class, 'exportPaymentsCsv'])
            ->middleware('permission:view_reports')
            ->name('reports.payments.export.csv');

        Route::get('/payments/export/pdf', [ReportsController::class, 'exportPaymentsPdf'])
            ->middleware('permission:view_reports')
            ->name('reports.payments.export.pdf');

        Route::get('/reports/promotions', [ReportsController::class, 'promotions'])
            ->middleware('permission:view_reports')
            ->name('reports.promotions');

        Route::get('/promotions/export/csv', [ReportsController::class, 'exportPromotionsCsv'])
            ->middleware('permission:view_reports')
            ->name('reports.promotions.export.csv');

        Route::get('/promotions/export/pdf', [ReportsController::class, 'exportPromotionsPdf'])
            ->middleware('permission:view_reports')
            ->name('reports.promotions.export.pdf');

        /*
        |--------------------------------------------------------------------------
        | Analysis
        |--------------------------------------------------------------------------
        */
        Route::get('/analysis', [AnalysisController::class, 'index'])
            ->middleware('permission:view_analysis')
            ->name('analysis.index');

        /*
        |--------------------------------------------------------------------------
        | Push Notifications
        |--------------------------------------------------------------------------
        |
        | 
        | 
        |--------------------------------------------------------------------------
        */
        Route::get('/notifitions', [PushNotificationController::class, 'index'])
            ->middleware('permission:view_notifications')
            ->name('notifitions.index');

        Route::post('/notifications', [PushNotificationController::class, 'store'])
            ->middleware('permission:view_notifications')
            ->name('notifications.store');

        Route::put('/{notification}', [PushNotificationController::class, 'update'])
            ->middleware('permission:view_notifications')
            ->name('update');

        Route::delete('/{notification}', [PushNotificationController::class, 'destroy'])
            ->middleware('permission:view_notifications')
            ->name('destroy');

        Route::post('/{notification}/resend', [PushNotificationController::class, 'resend'])
            ->middleware('permission:view_notifications')
            ->name('resend');

        /*
        |--------------------------------------------------------------------------
        | Settings
        |--------------------------------------------------------------------------
        */
        Route::get('/settings', [SettingController::class, 'index'])
            ->middleware('permission:view_settings')
            ->name('settings.index');

        Route::post('/settings', [SettingController::class, 'update'])
            ->middleware('permission:edit_settings')
            ->name('settings.update');

        /*
        |--------------------------------------------------------------------------
        | Roles & User Role Assignment
        |--------------------------------------------------------------------------
        */
        Route::get('/roles', [RoleController::class, 'index'])
            ->middleware('permission:view_roles')
            ->name('roles.index');

        Route::post('/roles', [RoleController::class, 'store'])
            ->middleware('permission:create_roles')
            ->name('roles.store');

        Route::put('/roles/{role}', [RoleController::class, 'update'])
            ->middleware('permission:edit_roles')
            ->name('roles.update');

        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
            ->middleware('permission:delete_roles')
            ->name('roles.destroy');

        // assign roles to users
        Route::get('/assign-roles', [RoleController::class, 'users'])
            ->middleware('permission:assign_roles')
            ->name('roles.users');

        Route::put('/assign-roles/{user}', [RoleController::class, 'assignUserRole'])
            ->middleware('permission:assign_roles')
            ->name('roles.assign-user');

        Route::post('/customers', [CustomersController::class, 'store'])
            ->middleware('permission:view_customers')
            ->name('admin.customers.store');

        Route::patch('/customers/{user}', [CustomersController::class, 'updateCustomer'])
            ->middleware('permission:view_customers')
            ->name('admin.updateCustomer');
    });

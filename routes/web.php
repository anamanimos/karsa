<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CashTransactionController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeAttendanceController;
use App\Http\Controllers\EmployeeBonusController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminPlanController;
use App\Http\Controllers\Admin\AdminBusinessController;
use Illuminate\Support\Facades\Route;

// Public route - redirect to login
Route::get('/', fn() => redirect()->route('login'));

// Documentation & User Guide
Route::get('/docs', fn() => view('docs.index'))->name('docs');

// All authenticated routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Master Data
    Route::resource('categories', CategoryController::class);
    Route::resource('units', UnitController::class);
    Route::resource('products', ProductController::class);
    Route::get('/products/search/json', [ProductController::class, 'search'])->name('products.search');
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);
    
    // Purchases
    Route::resource('purchases', PurchaseController::class);
    Route::get('/purchases/price-history', [PurchaseController::class, 'getPriceHistory'])->name('purchases.price-history');
    
    // Sales & POS Kasir
    Route::resource('sales', SaleController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
    Route::patch('/sales/{sale}/update-date', [SaleController::class, 'updateDate'])->name('sales.update-date');
    
    // Cash Registers / Shift Kasir
    Route::get('/cash-registers', [CashRegisterController::class, 'index'])->name('cash-registers.index');
    Route::post('/cash-registers/open', [CashRegisterController::class, 'open'])->name('cash-registers.open');
    Route::post('/cash-registers/{cashRegister}/close', [CashRegisterController::class, 'close'])->name('cash-registers.close');
    Route::get('/cash-registers/{cashRegister}', [CashRegisterController::class, 'show'])->name('cash-registers.show');

    // Payments (Hutang & Piutang)
    Route::get('/payments/suppliers', [PaymentController::class, 'supplierPayments'])->name('payments.suppliers');
    Route::post('/payments/suppliers', [PaymentController::class, 'storeSupplierPayment'])->name('payments.suppliers.store');
    Route::get('/payments/customers', [PaymentController::class, 'customerPayments'])->name('payments.customers');
    Route::post('/payments/customers', [PaymentController::class, 'storeCustomerPayment'])->name('payments.customers.store');
    
    // Stock Adjustments & Opname (Input Output Stok)
    Route::resource('stock-adjustments', StockAdjustmentController::class);

    // SDM & Penggajian (HR & Payroll)
    Route::get('/employees/attendance', [EmployeeAttendanceController::class, 'index'])->name('employee-attendances.index');
    Route::post('/employees/attendance', [EmployeeAttendanceController::class, 'store'])->name('employee-attendances.store');
    
    Route::get('/employees/bonuses', [EmployeeBonusController::class, 'index'])->name('employee-bonuses.index');
    Route::post('/employees/bonuses', [EmployeeBonusController::class, 'store'])->name('employee-bonuses.store');
    Route::delete('/employees/bonuses/{employeeBonus}', [EmployeeBonusController::class, 'destroy'])->name('employee-bonuses.destroy');

    Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
    Route::post('/payrolls/generate', [PayrollController::class, 'generate'])->name('payrolls.generate');
    Route::get('/payrolls/{payroll}/edit', [PayrollController::class, 'edit'])->name('payrolls.edit');
    Route::put('/payrolls/{payroll}', [PayrollController::class, 'update'])->name('payrolls.update');
    Route::post('/payrolls/{payroll}/approve', [PayrollController::class, 'approve'])->name('payrolls.approve');
    Route::post('/payrolls/{payroll}/pay', [PayrollController::class, 'pay'])->name('payrolls.pay');
    Route::get('/payrolls/{payroll}/slip', [PayrollController::class, 'slip'])->name('payrolls.slip');
    Route::delete('/payrolls/{payroll}', [PayrollController::class, 'destroy'])->name('payrolls.destroy');

    Route::resource('employees', EmployeeController::class);

    // Laporan Keuangan Sederhana (Financial Accounting Reports)
    Route::get('/financial-reports/profit-loss', [FinancialReportController::class, 'profitLoss'])->name('financial-reports.profit-loss');
    Route::get('/financial-reports/cash-flow', [FinancialReportController::class, 'cashFlow'])->name('financial-reports.cash-flow');
    Route::get('/financial-reports/balance-sheet', [FinancialReportController::class, 'balanceSheet'])->name('financial-reports.balance-sheet');
    Route::get('/financial-reports/general-ledger', [FinancialReportController::class, 'generalLedger'])->name('financial-reports.general-ledger');

    // Operational Reports (Sales, Purchases, Profit Legacy, Debts, Stock Ledger)
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/purchases', [ReportController::class, 'purchases'])->name('reports.purchases');
    Route::get('/reports/profit', [ReportController::class, 'profit'])->name('reports.profit');
    Route::get('/reports/debts', [ReportController::class, 'debts'])->name('reports.debts');
    Route::get('/reports/stock-movements', [StockMovementController::class, 'index'])->name('reports.stock_movements');
    
    // Settings (Profil Bisnis)
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    
    // Users Management
    Route::resource('users', UserController::class);
    
    // Cash Transactions
    Route::resource('cash-transactions', CashTransactionController::class);

    // Galleries
    Route::resource('galleries', GalleryController::class)->only(['index', 'store', 'destroy']);
    Route::get('/api/galleries', [GalleryController::class, 'apiIndex'])->name('api.galleries');
    Route::post('/galleries/{gallery}/labels', [GalleryController::class, 'updateLabels'])->name('galleries.update-labels');
    
    // Businesses Management & Active Switcher
    Route::post('/businesses/{business}/switch', [BusinessController::class, 'switch'])->name('businesses.switch');
    Route::resource('businesses', BusinessController::class);

    // Profile (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Platform Super Admin Routes (Protected by EnsureSuperAdmin Middleware)
Route::middleware(['auth', 'superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Platform Users Management
    Route::patch('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::resource('users', AdminUserController::class);

    // Platform Businesses / Tenants Management
    Route::patch('/businesses/{business}/toggle-status', [AdminBusinessController::class, 'toggleStatus'])->name('businesses.toggle-status');
    Route::post('/businesses/{business}/impersonate', [AdminBusinessController::class, 'impersonate'])->name('businesses.impersonate');
    Route::resource('businesses', AdminBusinessController::class)->only(['index', 'show']);

    // Platform Subscription Plans & Quota Management
    Route::get('/plans', [AdminPlanController::class, 'index'])->name('plans.index');
    Route::put('/plans/{plan}', [AdminPlanController::class, 'update'])->name('plans.update');
});

require __DIR__.'/auth.php';

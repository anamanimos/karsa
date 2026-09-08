<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CashRegister;
use App\Models\CashTransaction;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $thisMonthStart = Carbon::now()->startOfMonth();
        $thisMonthEnd = Carbon::now()->endOfMonth();

        // 1. Sales metrics
        $todaySales = Sale::whereDate('sale_date', $today)->sum('total_amount');
        $todayTransactions = Sale::whereDate('sale_date', $today)->count();
        $monthSales = Sale::whereBetween('sale_date', [$thisMonthStart, $thisMonthEnd])->sum('total_amount');

        // 2. Debts
        $totalReceivables = Sale::where('due_amount', '>', 0)->sum('due_amount');
        $totalPayables = Purchase::where('due_amount', '>', 0)->sum('due_amount');

        // 3. Cash & Bank Balance
        $cashIn = CashTransaction::where('type', 'in')->sum('amount');
        $cashOut = CashTransaction::where('type', 'out')->sum('amount');
        $initialAccounts = Account::where('subtype', 'cash_bank')->sum('initial_balance');
        $totalCash = $initialAccounts + $cashIn - $cashOut;

        // 4. Low stock products
        $lowStockProducts = Product::where('is_active', true)
            ->whereColumn('stock', '<=', 'min_stock')
            ->with(['category', 'sellUnit'])
            ->take(10)
            ->get();

        // 5. Recent sales
        $recentSales = Sale::with(['customer', 'cashier'])
            ->latest('sale_date')
            ->latest('id')
            ->take(5)
            ->get();

        // 6. HR & Payroll
        $activeEmployees = Employee::where('is_active', true)->count();
        $currentPeriod = Carbon::now()->format('Y-m');
        $payrollPending = Payroll::where('period', $currentPeriod)->whereIn('status', ['draft', 'approved'])->sum('net_salary');

        // 7. Cash register
        $currentRegister = CashRegister::currentOpenRegister();

        return view('dashboard', compact(
            'todaySales',
            'todayTransactions',
            'monthSales',
            'totalReceivables',
            'totalPayables',
            'totalCash',
            'lowStockProducts',
            'recentSales',
            'activeEmployees',
            'payrollPending',
            'currentRegister'
        ));
    }
}

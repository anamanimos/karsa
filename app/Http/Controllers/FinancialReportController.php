<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CashTransaction;
use App\Models\Customer;
use App\Models\CustomerPayment;
use App\Models\Payroll;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialReportController extends Controller
{
    /**
     * Laporan Laba Rugi (Income Statement)
     */
    public function profitLoss(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        // 1. Sales Revenue
        $sales = Sale::whereBetween('sale_date', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ])->get();

        $grossSales = $sales->sum('total_amount');
        $totalDiscount = $sales->sum('discount_amount');
        $netSalesRevenue = $grossSales;

        // 2. Other Revenues from Cash Transactions
        $otherRevenues = CashTransaction::where('type', 'in')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->where(function ($q) {
                $q->where('category', 'like', '%Pendapatan%')
                  ->orWhere('category', 'like', '%Jasa%')
                  ->orWhere('category', 'like', '%Lain%');
            })
            ->get();
        $totalOtherRevenue = $otherRevenues->sum('amount');

        $totalRevenue = $netSalesRevenue + $totalOtherRevenue;

        // 3. Cost of Goods Sold (HPP)
        $saleIds = $sales->pluck('id');
        $saleItems = SaleItem::whereIn('sale_id', $saleIds)->with('product')->get();

        $cogs = 0;
        foreach ($saleItems as $item) {
            $productCost = $item->product ? ($item->product->avg_purchase_price > 0 ? $item->product->avg_purchase_price : $item->product->last_purchase_price) : 0;
            $cogs += ($item->quantity * $productCost);
        }

        // Gross Profit
        $grossProfit = $totalRevenue - $cogs;

        // 4. Operating Expenses
        $expensesQuery = CashTransaction::where('type', 'out')
            ->whereBetween('transaction_date', [$startDate, $endDate]);

        // Group expenses by category
        $expensesByCategory = CashTransaction::where('type', 'out')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->select('category', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as total_count'))
            ->groupBy('category')
            ->get();

        $totalExpenses = $expensesByCategory->sum('total_amount');

        // Net Profit
        $netProfit = $grossProfit - $totalExpenses;

        return view('financial-reports.profit-loss', compact(
            'startDate',
            'endDate',
            'grossSales',
            'totalDiscount',
            'netSalesRevenue',
            'totalOtherRevenue',
            'totalRevenue',
            'cogs',
            'grossProfit',
            'expensesByCategory',
            'totalExpenses',
            'netProfit'
        ));
    }

    /**
     * Laporan Arus Kas Sederhana (Cash Flow)
     */
    public function cashFlow(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Initial Cash Balance before startDate
        $initialIn = CashTransaction::where('type', 'in')->where('transaction_date', '<', $startDate)->sum('amount');
        $initialOut = CashTransaction::where('type', 'out')->where('transaction_date', '<', $startDate)->sum('amount');
        
        // Account initial balance from accounts setup
        $accountBaseInitial = Account::where('subtype', 'cash_bank')->sum('initial_balance');
        $beginningCash = $accountBaseInitial + $initialIn - $initialOut;

        // Inflows in Period
        $cashSalesIn = Sale::whereBetween('sale_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn('payment_method', ['cash', 'qris', 'transfer'])
            ->sum('paid_amount');

        $debtCollectionsIn = CustomerPayment::whereBetween('payment_date', [$startDate, $endDate])->sum('amount');

        $otherInflows = CashTransaction::where('type', 'in')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->whereNull('reference_type')
            ->sum('amount');

        $totalInflow = $cashSalesIn + $debtCollectionsIn + $otherInflows;

        // Outflows in Period
        $cashPurchasesOut = Purchase::whereBetween('purchase_date', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->sum('paid_amount');

        $supplierDebtPaymentsOut = SupplierPayment::whereBetween('payment_date', [$startDate, $endDate])->sum('amount');

        $payrollOut = Payroll::whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->sum('net_salary');

        $otherExpensesOut = CashTransaction::where('type', 'out')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->where(function ($q) {
                $q->whereNull('reference_type')
                  ->orWhere('reference_type', '!=', Payroll::class);
            })
            ->sum('amount');

        $totalOutflow = $cashPurchasesOut + $supplierDebtPaymentsOut + $payrollOut + $otherExpensesOut;

        $netCashChange = $totalInflow - $totalOutflow;
        $endingCash = $beginningCash + $netCashChange;

        return view('financial-reports.cash-flow', compact(
            'startDate',
            'endDate',
            'beginningCash',
            'cashSalesIn',
            'debtCollectionsIn',
            'otherInflows',
            'totalInflow',
            'cashPurchasesOut',
            'supplierDebtPaymentsOut',
            'payrollOut',
            'otherExpensesOut',
            'totalOutflow',
            'netCashChange',
            'endingCash'
        ));
    }

    /**
     * Laporan Neraca Sederhana (Simple Balance Sheet)
     */
    public function balanceSheet(Request $request)
    {
        $asOfDate = $request->get('as_of_date', Carbon::now()->toDateString());

        // 1. Assets (Aktiva)
        // Cash & Bank
        $cashIn = CashTransaction::where('type', 'in')->where('transaction_date', '<=', $asOfDate)->sum('amount');
        $cashOut = CashTransaction::where('type', 'out')->where('transaction_date', '<=', $asOfDate)->sum('amount');
        $initialCashAccounts = Account::where('subtype', 'cash_bank')->sum('initial_balance');
        $totalCashBank = $initialCashAccounts + $cashIn - $cashOut;

        // Accounts Receivable (Piutang Pelanggan)
        $totalSalesCredit = Sale::where('sale_date', '<=', $asOfDate . ' 23:59:59')->sum('due_amount');
        $totalCustomerPayments = CustomerPayment::where('payment_date', '<=', $asOfDate)->sum('amount');
        $totalReceivable = max(0, $totalSalesCredit);

        // Inventory Value (Persediaan Barang Dagang)
        $products = Product::where('is_active', true)->get();
        $inventoryValue = 0;
        foreach ($products as $p) {
            $cost = $p->avg_purchase_price > 0 ? $p->avg_purchase_price : $p->last_purchase_price;
            $inventoryValue += max(0, $p->stock) * $cost;
        }

        $totalAssets = $totalCashBank + $totalReceivable + $inventoryValue;

        // 2. Liabilities (Kewajiban / Hutang)
        // Supplier Debt
        $totalPurchasesDebt = Purchase::where('purchase_date', '<=', $asOfDate)->sum('due_amount');
        $totalSupplierDebt = max(0, $totalPurchasesDebt);

        // 3. Equity (Modal & Laba Berjalan)
        $initialEquity = Account::where('type', 'equity')->sum('initial_balance');
        if ($initialEquity == 0) {
            $initialEquity = $totalAssets - $totalSupplierDebt;
        }

        // Net income to date
        $allGrossSales = Sale::where('sale_date', '<=', $asOfDate . ' 23:59:59')->sum('total_amount');
        $allExpenses = CashTransaction::where('type', 'out')->where('transaction_date', '<=', $asOfDate)->sum('amount');
        $retainedEarnings = $totalAssets - ($totalSupplierDebt + $initialEquity);

        $totalLiabilitiesAndEquity = $totalSupplierDebt + $initialEquity + $retainedEarnings;

        return view('financial-reports.balance-sheet', compact(
            'asOfDate',
            'totalCashBank',
            'totalReceivable',
            'inventoryValue',
            'totalAssets',
            'totalSupplierDebt',
            'initialEquity',
            'retainedEarnings',
            'totalLiabilitiesAndEquity'
        ));
    }

    /**
     * Buku Kas & Bank / Jurnal Kas
     */
    public function generalLedger(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());
        $accountId = $request->get('account_id');

        $query = CashTransaction::with(['account', 'creator', 'reference'])
            ->whereBetween('transaction_date', [$startDate, $endDate]);

        if ($accountId) {
            $query->where('account_id', $accountId);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->orderBy('id', 'desc')->paginate(20)->withQueryString();

        $accounts = Account::where('is_active', true)->orderBy('code')->get();

        $totalIn = (clone $query)->where('type', 'in')->sum('amount');
        $totalOut = (clone $query)->where('type', 'out')->sum('amount');
        $netChange = $totalIn - $totalOut;

        return view('financial-reports.general-ledger', compact(
            'transactions',
            'accounts',
            'startDate',
            'endDate',
            'accountId',
            'totalIn',
            'totalOut',
            'netChange'
        ));
    }
}

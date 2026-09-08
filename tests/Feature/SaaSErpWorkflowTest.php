<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\CashRegister;
use App\Models\CashTransaction;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeBonus;
use App\Models\Payroll;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockAdjustment;
use App\Models\StockAdjustmentItem;
use App\Models\StockMovement;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SaaSErpWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Employee $employee;
    protected Product $product;
    protected Account $cashAccount;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->admin = User::where('email', 'admin@postani.com')->first();
        if ($this->admin && $this->admin->active_business_id) {
            session(['active_business_id' => $this->admin->active_business_id]);
        }

        $this->employee = Employee::withoutGlobalScope('business')->first();
        if (!$this->employee) {
            $this->employee = Employee::create([
                'business_id' => $this->admin?->active_business_id ?? 1,
                'code' => 'EMP-001',
                'name' => 'Budi Santoso',
                'position' => 'Kasir & Staff Penjualan',
                'employment_status' => 'permanent',
                'join_date' => '2025-01-01',
                'base_salary' => 3000000,
                'fixed_allowance' => 500000,
                'daily_allowance' => 20000,
                'overtime_rate_per_hour' => 25000,
                'is_active' => true,
            ]);
        }

        $this->product = Product::withoutGlobalScope('business')->first();
        $this->cashAccount = Account::withoutGlobalScope('business')->where('code', '1-1001')->first() 
            ?? Account::withoutGlobalScope('business')->first();
    }

    public function test_dashboard_renders_successfully(): void
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Penjualan Hari Ini');
        $response->assertSee('Total Kas');
        $response->assertSee('toggleSidebar()');
        $response->assertSee('erp_sidebar_open');
        $response->assertSee('Sembunyikan Sidebar');
    }

    public function test_docs_page_renders_successfully(): void
    {
        // 1. As guest
        $guestResponse = $this->get(route('docs'));
        $guestResponse->assertStatus(200);
        $guestResponse->assertSee('Pusat Bantuan', false);
        $guestResponse->assertSee('Kasir & POS Penjualan', false);

        // 2. As authenticated user
        $authResponse = $this->actingAs($this->admin)->get(route('docs'));
        $authResponse->assertStatus(200);
        $authResponse->assertSee('Buku Panduan', false);
        $authResponse->assertSee('Kasir & POS Penjualan', false);
        $authResponse->assertSee('Stok & Manajemen Inventori', false);
        $authResponse->assertSee('Pengadaan & Pembelian', false);
        $authResponse->assertSee('Keuangan & Akuntansi', false);
        $authResponse->assertSee('SDM & Penggajian', false);
    }

    public function test_cash_register_shift_flow(): void
    {
        // 1. Open register
        $openResponse = $this->actingAs($this->admin)->post(route('cash-registers.open'), [
            'initial_cash' => 200000,
            'notes' => 'Shift Pagi',
        ]);
        $openResponse->assertRedirect(route('sales.create'));

        $register = CashRegister::currentOpenRegister($this->admin->id);
        $this->assertNotNull($register);
        $this->assertEquals(200000, $register->initial_cash);

        // 2. Perform POS Sale during shift
        $saleResponse = $this->actingAs($this->admin)->postJson(route('sales.store'), [
            'payment_method' => 'cash',
            'discount_amount' => 5000,
            'tax_amount' => 2000,
            'cashier_employee_id' => $this->employee->id,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 2,
                    'unit_price' => $this->product->selling_price,
                ],
            ],
        ]);
        $saleResponse->assertStatus(200);
        $sale = Sale::latest('id')->first();
        $this->assertEquals($register->id, $sale->cash_register_id);
        $this->assertEquals(5000, $sale->discount_amount);
        $this->assertEquals(2000, $sale->tax_amount);

        // 3. Close register
        $closeResponse = $this->actingAs($this->admin)->post(route('cash-registers.close', $register->id), [
            'actual_cash' => $register->initial_cash + $sale->paid_amount,
            'notes' => 'Kas sesuai perhitungan',
        ]);
        $closeResponse->assertRedirect(route('cash-registers.show', $register->id));

        $register->refresh();
        $this->assertEquals('closed', $register->status);
        $this->assertEquals(0, $register->difference);
    }

    public function test_stock_adjustment_flow(): void
    {
        $initialStock = $this->product->stock;
        $actualStock = $initialStock + 10;

        $response = $this->actingAs($this->admin)->post(route('stock-adjustments.store'), [
            'adjustment_date' => Carbon::today()->toDateString(),
            'type' => 'in_manual',
            'reason' => 'Bonus stok dari distributor',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'actual_stock' => $actualStock,
                    'notes' => 'Tambahan 10 unit',
                ],
            ],
        ]);

        $response->assertRedirect(route('stock-adjustments.index'));
        $this->product->refresh();
        $this->assertEquals($actualStock, $this->product->stock);

        $this->assertDatabaseHas('stock_adjustments', [
            'type' => 'in_manual',
            'reason' => 'Bonus stok dari distributor',
        ]);
    }

    public function test_hr_and_payroll_flow(): void
    {
        $period = Carbon::today()->format('Y-m');

        // 1. Record Attendance
        $attResponse = $this->actingAs($this->admin)->post(route('employee-attendances.store'), [
            'period' => $period,
            'attendances' => [
                [
                    'employee_id' => $this->employee->id,
                    'work_days' => 26,
                    'present_days' => 24,
                    'sick_days' => 1,
                    'permission_days' => 1,
                    'absent_days' => 0,
                    'overtime_hours' => 5,
                ],
            ],
        ]);
        $attResponse->assertRedirect(route('employee-attendances.index', ['period' => $period]));

        // 2. Record Bonus
        $bonusResponse = $this->actingAs($this->admin)->post(route('employee-bonuses.store'), [
            'employee_id' => $this->employee->id,
            'date' => Carbon::today()->toDateString(),
            'title' => 'Komisi Penjualan',
            'type' => 'commission',
            'amount' => 350000,
            'description' => 'Komisi penjualan kuartal 3',
        ]);
        $bonusResponse->assertStatus(302);

        $expectedBonus = EmployeeBonus::where('employee_id', $this->employee->id)->where('period', $period)->sum('amount');

        // 3. Generate Payroll
        $generateResponse = $this->actingAs($this->admin)->post(route('payrolls.generate'), [
            'period' => $period,
        ]);
        $generateResponse->assertRedirect(route('payrolls.index', ['period' => $period]));

        $payroll = Payroll::where('employee_id', $this->employee->id)->where('period', $period)->first();
        $this->assertNotNull($payroll);
        $this->assertEquals($expectedBonus, $payroll->bonus_pay);

        // 4. Approve Payroll
        $this->actingAs($this->admin)->post(route('payrolls.approve', $payroll->id));
        $payroll->refresh();
        $this->assertEquals('approved', $payroll->status);

        // 5. Pay Payroll (Disburse Cash & Record Expense)
        $payResponse = $this->actingAs($this->admin)->post(route('payrolls.pay', $payroll->id), [
            'payment_date' => Carbon::today()->toDateString(),
            'account_id' => $this->cashAccount->id,
            'notes' => 'Gaji ditransfer ke rekening Budi',
        ]);
        $payResponse->assertStatus(302);

        $payroll->refresh();
        $this->assertEquals('paid', $payroll->status);
        $this->assertNotNull($payroll->cash_transaction_id);

        $this->assertDatabaseHas('cash_transactions', [
            'id' => $payroll->cash_transaction_id,
            'type' => 'out',
            'amount' => $payroll->net_salary,
            'reference_type' => Payroll::class,
            'reference_id' => $payroll->id,
        ]);
    }

    public function test_financial_reports_render_successfully(): void
    {
        $today = Carbon::today()->toDateString();
        $startOfMonth = Carbon::today()->startOfMonth()->toDateString();

        // 1. Profit & Loss Report
        $plResponse = $this->actingAs($this->admin)->get(route('financial-reports.profit-loss', [
            'start_date' => $startOfMonth,
            'end_date' => $today,
        ]));
        $plResponse->assertStatus(200);
        $plResponse->assertSee('Laporan Laba Rugi');
        $plResponse->assertSee('Laba Bersih');

        // 2. Cash Flow Report
        $cfResponse = $this->actingAs($this->admin)->get(route('financial-reports.cash-flow', [
            'start_date' => $startOfMonth,
            'end_date' => $today,
        ]));
        $cfResponse->assertStatus(200);
        $cfResponse->assertSee('Laporan Arus Kas');
        $cfResponse->assertSee('SALDO AKHIR KAS');

        // 3. Balance Sheet Report
        $bsResponse = $this->actingAs($this->admin)->get(route('financial-reports.balance-sheet', [
            'as_of_date' => $today,
        ]));
        $bsResponse->assertStatus(200);
        $bsResponse->assertSee('Laporan Neraca');
        $bsResponse->assertSee('TOTAL AKTIVA');
        $bsResponse->assertSee('TOTAL PASIVA');

        // 4. General Ledger Report
        $glResponse = $this->actingAs($this->admin)->get(route('financial-reports.general-ledger', [
            'start_date' => $startOfMonth,
            'end_date' => $today,
        ]));
        $glResponse->assertStatus(200);
        $glResponse->assertSee('Buku Kas');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CashRegisterController extends Controller
{
    public function index()
    {
        $registers = CashRegister::with('user')
            ->orderBy('opened_at', 'desc')
            ->paginate(15);

        $currentRegister = CashRegister::currentOpenRegister();

        return view('cash-registers.index', compact('registers', 'currentRegister'));
    }

    public function open(Request $request)
    {
        $existing = CashRegister::currentOpenRegister();
        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah memiliki shift kasir yang sedang aktif.');
        }

        $validated = $request->validate([
            'initial_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $register = CashRegister::create([
            'user_id' => auth()->id(),
            'opened_at' => Carbon::now(),
            'initial_cash' => $validated['initial_cash'],
            'expected_cash' => $validated['initial_cash'],
            'status' => 'open',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('sales.create')->with('success', 'Shift kasir berhasil dibuka dengan modal awal Rp ' . number_format($register->initial_cash, 0, ',', '.'));
    }

    public function close(Request $request, CashRegister $cashRegister)
    {
        if ($cashRegister->status === 'closed') {
            return redirect()->back()->with('error', 'Shift kasir ini sudah ditutup sebelumnya.');
        }

        $validated = $request->validate([
            'actual_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Calculate sales during shift
        $sales = Sale::where('cash_register_id', $cashRegister->id)->get();
        $totalCashSales = $sales->where('payment_method', 'cash')->sum('paid_amount');
        $totalNonCashSales = $sales->whereIn('payment_method', ['qris', 'transfer', 'credit'])->sum('paid_amount');

        $expectedCash = $cashRegister->initial_cash + $totalCashSales + $cashRegister->total_cash_in - $cashRegister->total_cash_out;
        $actualCash = (float) $validated['actual_cash'];
        $difference = $actualCash - $expectedCash;

        $cashRegister->update([
            'closed_at' => Carbon::now(),
            'total_cash_sales' => $totalCashSales,
            'total_non_cash_sales' => $totalNonCashSales,
            'expected_cash' => $expectedCash,
            'actual_cash' => $actualCash,
            'difference' => $difference,
            'status' => 'closed',
            'notes' => $validated['notes'] ?? $cashRegister->notes,
        ]);

        return redirect()->route('cash-registers.show', $cashRegister->id)
            ->with('success', 'Shift kasir berhasil ditutup.');
    }

    public function show(CashRegister $cashRegister)
    {
        $cashRegister->load(['user', 'sales.customer', 'sales.saleItems.product']);
        return view('cash-registers.show', compact('cashRegister'));
    }
}

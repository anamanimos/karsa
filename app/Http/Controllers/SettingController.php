<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::getAllCached();

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['nullable', 'string', 'max:255'],
            'business_type' => ['nullable', 'string', 'max:50'],
            'company_phone' => ['nullable', 'string', 'max:50'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'company_address' => ['nullable', 'string', 'max:500'],
            'currency_symbol' => ['nullable', 'string', 'max:10'],
            'tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'min_margin' => ['required', 'numeric', 'min:0'],
            'receipt_footer' => ['nullable', 'string', 'max:500'],
            'require_shift_for_pos' => ['nullable', 'in:0,1'],
            'telegram_bot_token' => ['nullable', 'string', 'max:255'],
            'telegram_chat_id' => ['nullable', 'string', 'max:100'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value ?? '');
        }

        cache()->forget('app_settings');

        return redirect()
            ->route('settings.index')
            ->with('success', 'Pengaturan Profil Bisnis & Sistem berhasil disimpan.');
    }
}

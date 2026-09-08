<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk {{ $sale->invoice_number }}</title>
    <style>
        @page {
            margin: 5px;
        }
        body {
            font-family: 'Courier New', Courier, monospace, sans-serif;
            font-size: 10px;
            line-height: 1.2;
            color: #000;
            background-color: #fff;
            padding: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 8px;
        }
        .store-name {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .store-info {
            font-size: 8px;
            color: #333;
        }
        .separator {
            border-top: 1px dashed #000;
            margin: 4px 0;
        }
        .meta-info table {
            width: 100%;
            font-size: 8px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }
        .items-table th {
            text-align: left;
            border-bottom: 1px dashed #000;
            padding-bottom: 2px;
            font-size: 8px;
        }
        .items-table td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 9px;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            width: 100%;
            margin-top: 4px;
        }
        .totals td {
            padding: 1px 0;
            font-size: 9px;
        }
        .footer {
            text-align: center;
            margin-top: 12px;
            font-size: 8px;
        }
    </style>
</head>
<body>
    @php
        $companyName = $settings->get('company_name', $settings->get('store_name', 'KarsaERP'));
        $companyPhone = $settings->get('company_phone', $settings->get('store_phone', ''));
        $companyAddress = $settings->get('company_address', $settings->get('store_address', ''));
        $footerText = $settings->get('receipt_footer', 'Terima kasih atas kunjungan Anda!');
        $subtotalRaw = $sale->saleItems->sum('subtotal');
    @endphp

    <div class="header">
        <div class="store-name">{{ $companyName }}</div>
        @if($companyAddress)
            <div class="store-info">{{ $companyAddress }}</div>
        @endif
        @if($companyPhone)
            <div class="store-info">Telp/WA: {{ $companyPhone }}</div>
        @endif
    </div>

    <div class="separator"></div>

    <div class="meta-info">
        <table>
            <tr>
                <td>No: {{ $sale->invoice_number }}</td>
                <td class="text-right">Kasir: {{ $sale->cashier->name ?? ($sale->creator->name ?? 'Kasir') }}</td>
            </tr>
            <tr>
                <td>Tgl: {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</td>
                <td class="text-right">Plg: {{ $sale->customer->name ?? 'Walk-in (Umum)' }}</td>
            </tr>
        </table>
    </div>

    <div class="separator"></div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->saleItems as $item)
            <tr>
                <td>{{ $item->product->name ?? 'Item' }}</td>
                <td class="text-right">{{ $item->quantity }} {{ $item->product->sellUnit->symbol ?? '' }}</td>
                <td class="text-right">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="separator"></div>

    <table class="totals">
        @if($sale->discount_amount > 0 || $sale->tax_amount > 0)
        <tr>
            <td>Subtotal</td>
            <td class="text-right">Rp {{ number_format($subtotalRaw, 0, ',', '.') }}</td>
        </tr>
        @endif

        @if($sale->discount_amount > 0)
        <tr>
            <td>Diskon (-)</td>
            <td class="text-right">- Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}</td>
        </tr>
        @endif

        @if($sale->tax_amount > 0)
        <tr>
            <td>PPN (+)</td>
            <td class="text-right">+ Rp {{ number_format($sale->tax_amount, 0, ',', '.') }}</td>
        </tr>
        @endif

        <tr style="font-weight: bold;">
            <td>TOTAL TAGIHAN</td>
            <td class="text-right">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>METODE BAYAR</td>
            <td class="text-right uppercase">{{ $sale->payment_method === 'credit' ? 'HUTANG/TEMPO' : $sale->payment_method }}</td>
        </tr>
        <tr>
            <td>DIBAYAR</td>
            <td class="text-right">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</td>
        </tr>
        @if($sale->due_amount > 0)
        <tr style="color: #b91c1c; font-weight: bold;">
            <td>SISA PIUTANG (TEMPO)</td>
            <td class="text-right">Rp {{ number_format($sale->due_amount, 0, ',', '.') }}</td>
        </tr>
        @elseif($sale->paid_amount > $sale->total_amount)
        <tr>
            <td>KEMBALIAN</td>
            <td class="text-right">Rp {{ number_format($sale->paid_amount - $sale->total_amount, 0, ',', '.') }}</td>
        </tr>
        @endif
    </table>

    <div class="separator"></div>

    <div class="footer">
        <p>{{ $footerText }}</p>
        <p style="font-size:7px; color:#666;">Powered by KarsaERP</p>
    </div>
</body>
</html>

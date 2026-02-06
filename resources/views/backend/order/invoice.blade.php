<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $item->invoice_no }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 900px; margin: 0 auto; padding: 24px; }

        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .header-table td { padding: 12px; }
        .header-table .company-col { width: 50%; }
        .header-table .invoice-col { width: 50%; }

        .company-name { font-size: 28px; font-weight: bold; color: #252525; }
        .company-info { font-size: 13px; color: #666; line-height: 1.8; }

        .invoice-meta { text-align: right; font-size: 13px; }
        .invoice-meta-row { margin-bottom: 8px; }
        .invoice-meta-label { font-weight: bold; }

        .section-header { font-weight: bold; color: #252525; margin-bottom: 8px; margin-top: 16px; }

        .address-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .address-table td { width: 50%; vertical-align: top; padding: 12px; border: 1px solid #e6e6e6; }
        .address-header { font-weight: bold; color: #252525; margin-bottom: 8px; }
        .address-content { font-size: 13px; line-height: 1.8; }

        .items-table { width: 100%; border-collapse: collapse; margin-top: 24px; margin-bottom: 24px; }
        .items-table thead th { background: #f7f7f7; padding: 12px; text-align: left; font-weight: bold; border: 1px solid #e9e9e9; }
        .items-table tbody td { padding: 12px; border: 1px solid #e9e9e9; font-size: 13px; }
        .items-table .text-right { text-align: right; }
        .items-table .text-center { text-align: center; }

        .totals-table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        .totals-table td { padding: 10px; font-size: 13px; }
        .totals-table .label { text-align: right; padding-right: 20px; }
        .totals-table .amount { text-align: right; }
        .totals-table .subtotal { border-top: 1px solid #ddd; }
        .totals-table .grand-total { border-top: 2px solid #252525; font-weight: bold; font-size: 14px; }

        .footer { margin-top: 40px; font-size: 12px; color: #666; line-height: 1.8; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <table class="header-table">
            <tr>
                <td class="company-col">
                    <div class="company-name">{{ getSetting('business_name') }}</div>
                    <div class="company-info" style="margin-top: 12px;">
                        {!! nl2br(e(getSetting('address'))) !!}<br>
                        Phone: {{ getSetting('phone') }}<br>
                        Email: {{ getSetting('contact_email') ?? getSetting('email') }}
                    </div>
                </td>
                <td class="invoice-col">
                    <h1 style="font-size: 28px; margin: 0; color: #252525;">Invoice</h1>
                    <div class="invoice-meta" style="margin-top: 16px;">
                        <div class="invoice-meta-row">
                            <span class="invoice-meta-label">Invoice #:</span> {{ $item->invoice_no }}
                        </div>
                        <div class="invoice-meta-row">
                            <span class="invoice-meta-label">Date:</span> {{ $item->created_at->format('d M, Y') }}
                        </div>
                        <div class="invoice-meta-row">
                            <span class="invoice-meta-label">Status:</span>
                            <span style="color: @if($item->payment_status == 1) green @elseif($item->payment_status == 2) orange @else red @endif;">
                                {{ $item->payment_status == 1 ? 'Paid' : ($item->payment_status == 2 ? 'Partial' : 'Unpaid') }}
                            </span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Bill and Ship To Section -->
        <table class="address-table">
            <tr>
                <td>
                    <div class="address-header">Bill To</div>
                    <div class="address-content">
                        <strong>{{ $item->name }}</strong><br>
                        {{ $item->email }}<br>
                        {{ $item->phone }}<br>
                        {{ $item->address }}
                    </div>
                </td>
                <td>
                    <div class="address-header">Ship To</div>
                    <div class="address-content">
                        <strong>{{ $item->name }}</strong><br>
                        {{ $item->city->name ?? '' }}, {{ $item->state->name ?? '' }}<br>
                        {{ $item->country->name ?? '' }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item->items as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            {{ $row->product_name }}
                            @if($row->variant_name)
                                <br><small style="color: #999;">{{ $row->variant_name }}</small>
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($row->price, 2) }}</td>
                        <td class="text-center">{{ $row->quantity }}</td>
                        <td class="text-right">{{ number_format($row->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Section -->
        <table class="totals-table" style="width: 50%; margin-left: auto;">
            <tr class="subtotal">
                <td class="label">Subtotal:</td>
                <td class="amount">{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @if($item->tax > 0)
            <tr>
                <td class="label">Tax:</td>
                <td class="amount">{{ number_format($item->tax, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Shipping:</td>
                <td class="amount">{{ number_format($item->shipping_cost, 2) }}</td>
            </tr>
            @if($item->discount > 0)
            <tr>
                <td class="label">Discount:</td>
                <td class="amount">-{{ number_format($item->discount, 2) }}</td>
            </tr>
            @endif
            <tr class="grand-total">
                <td class="label">Grand Total:</td>
                <td class="amount" style="color: #e7ab3c;">{{ number_format($item->grand_total, 2) }}</td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank You!</strong><br>
            Thank you for your business. If you have any questions regarding this invoice, please contact us at {{ getSetting('contact_email') ?? getSetting('email') }}.</p>
        </div>
    </div>
</body>
</html>

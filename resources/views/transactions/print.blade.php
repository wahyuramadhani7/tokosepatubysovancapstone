<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $transaction->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 10px;
            width: 80mm;
            color: #000;
        }
        .receipt {
            max-width: 100%;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .store-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .store-address {
            font-size: 11px;
            margin-bottom: 5px;
        }
        .store-contact {
            font-size: 11px;
            margin-bottom: 10px;
        }
        .invoice-info {
            margin-bottom: 12px;
        }
        .invoice-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 11px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .items {
            margin-bottom: 12px;
        }
        .item {
            margin-bottom: 8px;
        }
        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }
        .item-details span:first-child {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .summary {
            margin-top: 10px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-top: 5px;
            font-size: 12px;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
        }
        @media print {
            body {
                width: 80mm;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="store-name">Sepatu by Sovan</div>
            <div class="store-address">Jl. Puri Anjasmoro Blok B 10 no 9 Smg</div>
            <div class="store-contact">08818671005</div>
        </div>

        <div class="invoice-info">
            <div class="invoice-row">
                <span>Invoice:</span>
                <span>{{ $transaction->invoice_number }}</span>
            </div>
            <div class="invoice-row">
                <span>Date:</span>
                <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="invoice-row">
                <span>Cashier:</span>
                <span>{{ $transaction->user->name ?? 'Unknown' }}</span>
            </div>
            <div class="invoice-row">
                <span>Customer:</span>
                <span>{{ $transaction->customer_name ?? 'Tanpa Nama' }}</span>
            </div>
            @if($transaction->customer_phone)
            <div class="invoice-row">
                <span>Phone:</span>
                <span>{{ $transaction->customer_phone }}</span>
            </div>
            @endif
            @if($transaction->customer_email)
            <div class="invoice-row">
                <span>Email:</span>
                <span>{{ $transaction->customer_email }}</span>
            </div>
            @endif
        </div>

        <div class="divider"></div>

        <div class="items">
            @foreach($transaction->items as $item)
            <div class="item">
                <div>
                    @if($item->product)
                        {{ $item->product->name }} 
                        @if($item->productUnit)
                            ({{ $item->productUnit->unit_code }})
                        @else
                            (Unit Tidak Tersedia)
                        @endif
                    @else
                        Produk Tidak Tersedia
                    @endif
                </div>
                <div class="item-details">
                    <span>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($item->product && ($item->product->color || $item->product->size))
                <div class="item-details">
                    <span>
                        @if($item->product->color) {{ $item->product->color }} @endif
                        @if($item->product->size) {{ $item->product->size }} @endif
                    </span>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <div class="divider"></div>

        <div class="summary">
            <div class="invoice-row">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
            @if($transaction->discount_amount > 0)
            <div class="invoice-row">
                <span>Discount:</span>
                <span>Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-row">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}</span>
            </div>
            <div class="invoice-row">
                <span>Payment:</span>
                <span>
                    @switch($transaction->payment_method)
                        @case('cash') Tunai @break
                        @case('credit_card') QRIS @break
                        @case('transfer') Transfer Bank @break
                        @default {{ $transaction->payment_method }}
                    @endswitch
                </span>
            </div>
            @if($transaction->notes)
            <div class="invoice-row">
                <span>Notes:</span>
                <span>{{ $transaction->notes }}</span>
            </div>
            @endif
        </div>

        <div class="divider"></div>

        <div class="footer">
            <p>Thank you for your purchase!</p>
            <p>Barang yang sudah dibeli tidak dapat dikembalikan.</p>
        </div>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print();" style="padding: 8px 16px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Print Receipt</button>
        <a href="{{ route('transactions.index') }}" style="padding: 8px 16px; margin-left: 10px; background-color: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 12px;">Close</a>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
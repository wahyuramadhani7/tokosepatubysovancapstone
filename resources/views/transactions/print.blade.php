<!DOCTYPE html>
<html lang="en">
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
            padding: 20px;
            width: 80mm;
        }
        .receipt {
            max-width: 100%;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .store-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .store-address {
            margin-bottom: 5px;
        }
        .store-contact {
            margin-bottom: 10px;
        }
        .invoice-info {
            margin-bottom: 15px;
        }
        .invoice-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .items {
            margin-bottom: 15px;
        }
        .item {
            margin-bottom: 8px;
        }
        .item-details {
            display: flex;
            justify-content: space-between;
        }
        .summary {
            margin-top: 10px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-top: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
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
                <span>{{ $transaction->user->name }}</span>
            </div>
            @if($transaction->customer_name)
            <div class="invoice-row">
                <span>Customer:</span>
                <span>{{ $transaction->customer_name }}</span>
            </div>
            @endif
        </div>

        <div class="divider"></div>

        <div class="items">
            @foreach($transaction->items as $item)
            <div class="item">
                <div>{{ $item->product->name }}</div>
                <div class="item-details">
                    <span>{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
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
            <div class="invoice-row">
                <span>Tax (11%):</span>
                <span>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}</span>
            </div>
            <div class="invoice-row">
                <span>Payment:</span>
                <span>{{ ucfirst($transaction->payment_method) }}</span>
            </div>
        </div>

        <div class="divider"></div>

        <div class="footer">
            <p>Thank you for your purchase!</p>
            <p>Barang yang sudah dibeli tidak dapat dikembalikan.</p>
        </div>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print();" style="padding: 8px 16px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">Print Receipt</button>
        <button onclick="window.close();" style="padding: 8px 16px; margin-left: 10px; background-color: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer;">Close</button>
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
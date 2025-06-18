<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - INV-20250618-08</title>
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
                <span>INV-20250618-08</span>
            </div>
            <div class="invoice-row">
                <span>Date:</span>
                <span>18/06/2025 19:25</span> <!-- Updated to current time -->
            </div>
            <div class="invoice-row">
                <span>Cashier:</span>
                <span>Pemilik Test</span>
            </div>
            <div class="invoice-row">
                <span>Customer:</span>
                <span>Wahyu Ramadhani</span>
            </div>
        </div>

        <div class="divider"></div>

        <div class="items">
            <div class="item">
                <div>Jordan Junior</div>
                <div class="item-details">
                    <span>1 x Rp 12.000.000</span>
                    <span>Rp 12.000.000</span>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="summary">
            <div class="invoice-row">
                <span>Subtotal:</span>
                <span>Rp 12.000.000</span>
            </div>
            <div class="total-row">
                <span>TOTAL:</span>
                <span>Rp 12.000.000</span>
            </div>
            <div class="invoice-row">
                <span>Payment:</span>
                <span>Cash</span>
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
        <button onclick="window.location.href='{{ route('transactions.index') }}';" style="padding: 8px 16px; margin-left: 10px; background-color: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer;">Close</button>
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
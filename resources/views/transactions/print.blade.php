<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $transaction->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 5px;
            width: 80mm;
            color: #000;
            box-sizing: border-box;
        }
        .receipt {
            width: 100%;
            max-width: 80mm;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .store-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .store-address, .store-contact {
            font-size: 10px;
            margin-bottom: 3px;
        }
        .invoice-info {
            margin-bottom: 8px;
        }
        .invoice-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 10px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        .items {
            margin-bottom: 8px;
        }
        .item {
            margin-bottom: 6px;
        }
        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }
        .item-details span:first-child {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .summary {
            margin-top: 6px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-top: 4px;
            font-size: 12px;
        }
        .footer {
            text-align: center;
            margin-top: 8px;
            font-size: 9px;
        }
        @media print {
            body {
                width: 80mm;
                margin: 0;
                padding: 0;
                font-size: 12px;
            }
            .no-print {
                display: none;
            }
            .receipt {
                width: 80mm;
                margin: 0;
            }
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="store-name">Sepatu by Sovan</div>
            <div class="store-address">Jl. Puri Anjasmoro B10/9 Smg</div>
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
                        {{ Str::limit($item->product->name, 28) }} 
                        @if($item->productUnit)
                            ({{ $item->productUnit->unit_code }})
                        @else
                            (Unit N/A)
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
                <span>Total Quantity:</span>
                <span>{{ $transaction->items->sum('quantity') }}</span>
            </div>
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
                <span>{{ Str::limit($transaction->notes, 28) }}</span>
            </div>
            @endif
        </div>

        <div class="divider"></div>

        <div class="footer">
            <p>Thank you for your purchase!</p>
            <p>Barang Yang Tidak Sesuai dapat di Tukarkan.</p>
        </div>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="connectAndPrint()" style="padding: 8px 16px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">Connect & Print via Bluetooth</button>
        <a href="{{ route('transactions.index') }}" style="padding: 8px 16px; margin-left: 10px; background-color: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 14px;">Close</a>
        <div id="status" style="margin-top: 10px; font-size: 14px;"></div>
    </div>

    <script>
        // Fungsi untuk memformat teks rata tengah
        function centerText(text, width = 48) {
            const cleanText = String(text).replace(/[^ -~]/g, '');
            const padding = Math.floor((width - cleanText.length) / 2);
            return ' '.repeat(padding >= 0 ? padding : 0) + cleanText;
        }

        // Fungsi untuk padding kanan
        function padRight(text, length) {
            const cleanText = String(text).replace(/[^ -~]/g, '').substring(0, length);
            return cleanText + ' '.repeat(length - cleanText.length);
        }

        // Fungsi untuk memotong teks panjang
        function truncateText(text, maxLength) {
            const cleanText = String(text).replace(/[^ -~]/g, '');
            return cleanText.length > maxLength ? cleanText.substring(0, maxLength - 3) + '...' : cleanText;
        }

        // Fungsi untuk mengambil konten receipt sebagai teks
        function getReceiptContent() {
            let content = '';
            
            // ESC/POS: Initialize printer + set font normal
            const initPrinter = new Uint8Array([0x1B, 0x40, 0x1B, 0x4D, 0x00]);
            content += String.fromCharCode(...initPrinter);

            // Header
            content += centerText('Sepatu by Sovan') + '\n';
            content += centerText('Jl. Puri Anjasmoro B10/9 Smg') + '\n';
            content += centerText('08818671005') + '\n';
            content += '-'.repeat(48) + '\n';

            // Invoice Info
            content += `${padRight('Invoice:', 15)}${truncateText('{{ $transaction->invoice_number }}', 33)}\n`;
            content += `${padRight('Date:', 15)}${truncateText('{{ $transaction->created_at->format('d/m/Y H:i') }}', 33)}\n`;
            content += `${padRight('Cashier:', 15)}${truncateText('{{ $transaction->user->name ?? 'Unknown' }}', 33)}\n`;
            content += `${padRight('Customer:', 15)}${truncateText('{{ $transaction->customer_name ?? 'Tanpa Nama' }}', 33)}\n`;
            @if($transaction->customer_phone)
            content += `${padRight('Phone:', 15)}${truncateText('{{ $transaction->customer_phone }}', 33)}\n`;
            @endif
            @if($transaction->customer_email)
            content += `${padRight('Email:', 15)}${truncateText('{{ $transaction->customer_email }}', 33)}\n`;
            @endif
            content += '-'.repeat(48) + '\n';

            // Items
            @foreach($transaction->items as $item)
            content += truncateText(`{{ $item->product ? $item->product->name : 'Produk Tidak Tersedia' }}`, 28);
            @if($item->productUnit)
            content += ` ({{ $item->productUnit->unit_code }})\n`;
            @else
            content += ` (Unit N/A)\n`;
            @endif
            content += `${padRight('{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}', 24)}Rp {{ number_format($item->subtotal, 0, ',', '.') }}\n`;
            @if($item->product && ($item->product->color || $item->product->size))
            content += `  ${truncateText(`@if($item->product->color) {{ $item->product->color }} @endif @if($item->product->size) {{ $item->product->size }} @endif`, 44)}\n`;
            @endif
            @endforeach
            content += '-'.repeat(48) + '\n';

            // Summary
            content += `${padRight('Total Quantity:', 15)}{{ $transaction->items->sum('quantity') }}\n`;
            content += `${padRight('Subtotal:', 15)}Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}\n`;
            @if($transaction->discount_amount > 0)
            content += `${padRight('Discount:', 15)}Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}\n`;
            @endif
            content += `${padRight('TOTAL:', 15)}Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}\n`;
            content += `${padRight('Payment:', 15)}${truncateText(`@switch($transaction->payment_method) @case('cash') Tunai @break @case('credit_card') QRIS @break @case('transfer') Transfer Bank @break @default {{ $transaction->payment_method }} @endswitch`, 33)}\n`;
            @if($transaction->notes)
            content += `${padRight('Notes:', 15)}${truncateText('{{ $transaction->notes }}', 33)}\n`;
            @endif
            content += '-'.repeat(48) + '\n\n';

            // Footer
            content += centerText('Thank you for your purchase!') + '\n';
            content += centerText('Barang dibeli tidak dikembalikan') + '\n\n';

            // ESC/POS: Cut paper
            content += String.fromCharCode(0x1D, 0x56, 0x00);

            return content;
        }

        // Fungsi untuk memecah buffer menjadi chunks
        function chunkBuffer(buffer, chunkSize) {
            const chunks = [];
            for (let i = 0; i < buffer.length; i += chunkSize) {
                chunks.push(buffer.slice(i, i + chunkSize));
            }
            return chunks;
        }

        // Fungsi untuk menunda eksekusi
        function delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        // Fungsi untuk koneksi dan cetak ke printer Bluetooth
        async function connectAndPrint() {
            const status = document.getElementById('status');
            try {
                // Cari perangkat Bluetooth
                const device = await navigator.bluetooth.requestDevice({
                    acceptAllDevices: true,
                    optionalServices: ['000018f0-0000-1000-8000-00805f9b34fb']
                });

                status.textContent = 'Connecting to ' + device.name + '...';
                
                // Sambungkan ke perangkat
                const server = await device.gatt.connect();
                const service = await server.getPrimaryService('000018f0-0000-1000-8000-00805f9b34fb');
                const characteristic = await service.getCharacteristic('00002af1-0000-1000-8000-00805f9b34fb');

                // Dapatkan konten receipt
                const receiptContent = getReceiptContent();

                // Konversi teks ke buffer
                const encoder = new TextEncoder('utf-8');
                const buffer = encoder.encode(receiptContent);

                // Pecah buffer menjadi chunks (128 bytes untuk stabilitas)
                const chunkSize = 128;
                const chunks = chunkBuffer(buffer, chunkSize);

                // Kirim setiap chunk ke printer dengan penundaan
                for (let i = 0; i < chunks.length; i++) {
                    await characteristic.writeValue(chunks[i]);
                    status.textContent = `Sending chunk ${i + 1}/${chunks.length}...`;
                    await delay(150);
                }

                status.textContent = 'Printing successful!';
                // Redirect ke halaman index setelah 2 detik
                setTimeout(() => {
                    window.location.href = '{{ route('transactions.index') }}';
                }, 2000);
            } catch (error) {
                status.textContent = 'Error: ' + error.message;
                console.error('Bluetooth Error:', error);
            }
        }
    </script>
</body>
</html>
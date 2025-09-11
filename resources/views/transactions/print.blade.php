<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $transaction->invoice_number }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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
<body onload="autoConnectAndPrint()">
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
            @if($transaction->transaction_type === 'online')
            <div class="invoice-row">
                <span>Platform:</span>
                <span>{{ $transaction->online_platform ?? '-' }}</span>
            </div>
            <div class="invoice-row">
                <span>Courier:</span>
                <span>{{ $transaction->courier ?? '-' }}</span>
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
                    @php
                        $paymentMethod = $transaction->payment_method;
                        if (strpos($paymentMethod, 'debit_') === 0) {
                            $cardType = ucfirst(str_replace('debit_', '', $paymentMethod));
                            echo "Debit $cardType";
                        } elseif ($paymentMethod === 'qris') {
                            echo 'QRIS';
                        } elseif ($paymentMethod === 'cash') {
                            echo 'Tunai';
                        } elseif ($paymentMethod === 'transfer') {
                            echo 'Transfer Bank';
                        } else {
                            echo $paymentMethod;
                        }
                    @endphp
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
            <p>Barang Tidak Sesuai Dapat Dtukarkan, Asalkan Belum Dipakai.</p>
            <p>Gabung grup WhatsApp kami untuk info</p>
            <p>diskon dan penawaran menarik!</p>
            <p>https://chat.whatsapp.com/CSXlDpfDfq92STaQKJ2a58?mode=ac_t</p>
        </div>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="connectAndPrint()" style="padding: 8px 16px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">Connect & Print via Bluetooth</button>
        @if($transaction->customer_phone)
        <button onclick="sendToWhatsApp()" style="padding: 8px 16px; margin-left: 10px; background-color: #25D366; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">Kirim ke WhatsApp</button>
        @endif
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

        // Fungsi untuk memformat nomor telepon ke format internasional
        function formatPhoneNumber(phone) {
            let cleanPhone = phone.replace(/\D/g, '');
            if (cleanPhone.startsWith('0')) {
                cleanPhone = '+62' + cleanPhone.slice(1);
            } else if (!cleanPhone.startsWith('+')) {
                cleanPhone = '+62' + cleanPhone;
            }
            return cleanPhone;
        }

        // Fungsi untuk mengambil konten receipt sebagai teks untuk WhatsApp
        function getWhatsAppContent() {
            let content = `*Halo {{ $transaction->customer_name ?? 'Pelanggan' }}!*\n`;
            content += `Terima kasih atas pembelian Anda di *Sepatu by Sovan*!\n`;
            content += `Dapatkan info diskon dan penawaran menarik dengan bergabung di grup WhatsApp kami:\n`;
            content += `https://chat.whatsapp.com/CSXlDpfDfq92STaQKJ2a58?mode=ac_t\n\n`;
            content += `*Struk Pembelian*\n`;
            content += `Jl. Puri Anjasmoro B10/9 Smg\n`;
            content += `08818671005\n`;
            content += `----------------------------------------\n`;
            content += `Invoice: ${truncateText('{{ $transaction->invoice_number }}', 33)}\n`;
            content += `Tanggal: ${truncateText('{{ $transaction->created_at->format('d/m/Y H:i') }}', 33)}\n`;
            content += `Kasir: ${truncateText('{{ $transaction->user->name ?? 'Unknown' }}', 33)}\n`;
            content += `Pelanggan: ${truncateText('{{ $transaction->customer_name ?? 'Tanpa Nama' }}', 33)}\n`;
            @if($transaction->customer_phone)
            content += `Telepon: ${truncateText('{{ $transaction->customer_phone }}', 33)}\n`;
            @endif
            @if($transaction->customer_email)
            content += `Email: ${truncateText('{{ $transaction->customer_email }}', 33)}\n`;
            @endif
            @if($transaction->transaction_type === 'online')
            content += `Platform: ${truncateText('{{ $transaction->online_platform ?? '-' }}', 33)}\n`;
            content += `Kurir: ${truncateText('{{ $transaction->courier ?? '-' }}', 33)}\n`;
            @endif
            content += `----------------------------------------\n`;

            // Items
            @foreach($transaction->items as $item)
            content += `${truncateText(`{{ $item->product ? $item->product->name : 'Produk Tidak Tersedia' }}`, 28)}`;
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
            content += `----------------------------------------\n`;

            // Summary
            content += `Jumlah Item: {{ $transaction->items->sum('quantity') }}\n`;
            content += `Subtotal: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}\n`;
            @if($transaction->discount_amount > 0)
            content += `Diskon: Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}\n`;
            @endif
            content += `*TOTAL: Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}*\n`;
            content += `Pembayaran: ${truncateText(`@php
                $paymentMethod = $transaction->payment_method;
                if (strpos($paymentMethod, 'debit_') === 0) {
                    $cardType = ucfirst(str_replace('debit_', '', $paymentMethod));
                    echo "Debit $cardType";
                } elseif ($paymentMethod === 'qris') {
                    echo 'QRIS';
                } elseif ($paymentMethod === 'cash') {
                    echo 'Tunai';
                } elseif ($paymentMethod === 'transfer') {
                    echo 'Transfer Bank';
                } else {
                    echo $paymentMethod;
                }
                @endphp`, 33)}\n`;
            @if($transaction->notes)
            content += `Catatan: ${truncateText('{{ $transaction->notes }}', 33)}\n`;
            @endif
            content += `----------------------------------------\n`;
            content += `Terima kasih atas pembelian Anda!\n`;
            content += `Barang yang tidak sesuai dapat ditukarkan.\n`;

            console.log('WhatsApp Content:', content); // Logging untuk debugging
            return content;
        }

        // Fungsi untuk mengirim ke WhatsApp
        function sendToWhatsApp() {
            @if($transaction->customer_phone)
            const phone = formatPhoneNumber('{{ $transaction->customer_phone }}');
            const message = encodeURIComponent(getWhatsAppContent());
            const url = `https://wa.me/${phone}?text=${message}`;
            window.open(url, '_blank');
            @else
            document.getElementById('status').textContent = 'Error: Nomor telepon pelanggan tidak tersedia.';
            @endif
        }

        // Fungsi untuk mengambil konten receipt untuk printer
        function getReceiptContent() {
            let content = '';
            
            // Initialize printer (basic reset, compatible with most non-thermal printers)
            const initPrinter = new Uint8Array([0x1B, 0x40]); // ESC @ (reset printer)
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
            @if($transaction->transaction_type === 'online')
            content += `${padRight('Platform:', 15)}${truncateText('{{ $transaction->online_platform ?? '-' }}', 33)}\n`;
            content += `${padRight('Courier:', 15)}${truncateText('{{ $transaction->courier ?? '-' }}', 33)}\n`;
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
            content += `${padRight('Payment:', 15)}${truncateText(`@php
                $paymentMethod = $transaction->payment_method;
                if (strpos($paymentMethod, 'debit_') === 0) {
                    $cardType = ucfirst(str_replace('debit_', '', $paymentMethod));
                    echo "Debit $cardType";
                } elseif ($paymentMethod === 'qris') {
                    echo 'QRIS';
                } elseif ($paymentMethod === 'cash') {
                    echo 'Tunai';
                } elseif ($paymentMethod === 'transfer') {
                    echo 'Transfer Bank';
                } else {
                    echo $paymentMethod;
                }
                @endphp`, 33)}\n`;
            @if($transaction->notes)
            content += `${padRight('Notes:', 15)}${truncateText('{{ $transaction->notes }}', 33)}\n`;
            @endif
            content += '-'.repeat(48) + '\n\n';

            // Footer
            content += centerText('Thank you for your purchase!') + '\n';
            content += centerText('Barang Tidak Sesuai Dapat Ditukarkan, Asalkan Belum Dipakai') + '\n';
            content += centerText('Bisa masuk ke link dibawah ini') + '\n';
            content += centerText('Dapatkan info diskon menarik!') + '\n';
            content += centerText('WA: https://chat.whatsapp.com/CSXlDpfDfq92STaQKJ2a58?mode=ac_t') + '\n\n';

            // Cut paper (if supported)
            content += String.fromCharCode(0x1D, 0x56, 0x00);

            console.log('Receipt Content:', content); // Logging untuk debugging
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

        // Fungsi untuk menyimpan informasi perangkat ke localStorage
        function savePrinterDevice(device) {
            localStorage.setItem('printerDevice', JSON.stringify({
                name: device.name,
                id: device.id
            }));
        }

        // Fungsi untuk mendapatkan informasi perangkat dari localStorage
        function getSavedPrinter() {
            const printer = localStorage.getItem('printerDevice');
            return printer ? JSON.parse(printer) : null;
        }

        // Fungsi untuk koneksi dan cetak ke printer Bluetooth
        async function connectAndPrint(manual = false) {
            const status = document.getElementById('status');
            try {
                let device;
                const savedPrinter = getSavedPrinter();

                if (!manual && savedPrinter) {
                    // Coba koneksi ke printer yang tersimpan
                    status.textContent = `Mencoba menyambung ke ${savedPrinter.name}...`;
                    device = await navigator.bluetooth.requestDevice({
                        filters: [{ name: savedPrinter.name }],
                        optionalServices: ['000018f0-0000-1000-8000-00805f9b34fb']
                    });
                } else {
                    // Pilih perangkat secara manual
                    status.textContent = 'Mencari perangkat Bluetooth...';
                    device = await navigator.bluetooth.requestDevice({
                        acceptAllDevices: true,
                        optionalServices: ['000018f0-0000-1000-8000-00805f9b34fb']
                    });
                    savePrinterDevice(device); // Simpan perangkat yang dipilih
                }

                status.textContent = `Menyambung ke ${device.name}...`;
                
                // Sambungkan ke perangkat
                const server = await device.gatt.connect();
                const service = await server.getPrimaryService('000018f0-0000-1000-8000-00805f9b34fb');
                const characteristic = await service.getCharacteristic('00002af1-0000-1000-8000-00805f9b34fb');

                // Cetak teks receipt
                const receiptContent = getReceiptContent();
                const encoder = new TextEncoder('utf-8');
                let buffer = encoder.encode(receiptContent);
                let chunks = chunkBuffer(buffer, 128);
                for (let i = 0; i < chunks.length; i++) {
                    await characteristic.writeValue(chunks[i]);
                    status.textContent = `Mengirim potongan teks ${i + 1}/${chunks.length}...`;
                    await delay(150);
                }

                status.textContent = 'Pencetakan berhasil!';
                // Redirect ke halaman index setelah 2 detik
                setTimeout(() => {
                    window.location.href = '{{ route('transactions.index') }}';
                }, 2000);
            } catch (error) {
                status.textContent = 'Error: ' + error.message;
                console.error('Bluetooth Error:', error);
                if (!manual) {
                    status.textContent += ' Silakan pilih printer secara manual.';
                }
            }
        }

        // Fungsi untuk mencoba koneksi otomatis saat halaman dimuat
        async function autoConnectAndPrint() {
            if (navigator.bluetooth) {
                const savedPrinter = getSavedPrinter();
                if (savedPrinter) {
                    await connectAndPrint(false); // Coba koneksi otomatis
                } else {
                    document.getElementById('status').textContent = 'Tidak ada printer tersimpan. Silakan pilih printer secara manual.';
                }
            } else {
                document.getElementById('status').textContent = 'Error: Browser tidak mendukung Web Bluetooth API.';
            }
        }
    </script>
</body>
</html>
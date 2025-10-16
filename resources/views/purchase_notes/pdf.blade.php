<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Catatan Pembelian</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        h1 { text-align: center; color: #000; }
        h2 { text-transform: capitalize; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f28c38; color: #000; font-weight: bold; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .capitalize { text-transform: capitalize; }
        .uppercase { text-transform: uppercase; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h1>CATATAN BARANG MASUK</h1>
    @php
        $rowNumber = 0;
    @endphp
    @forelse ($purchaseNotes->groupBy('type') as $type => $notes)
        <h2 class="capitalize">{{ $type }}</h2>
        <table>
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Jenis</th>
                    <th>Nama Produk</th>
                    <th class="text-center">Ukuran</th>
                    <th class="text-center">Warna</th>
                    <th class="text-right">Harga Asli</th>
                    <th class="text-right">Harga Diskon</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center">Dibuat Oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($notes as $note)
                    @php
                        $rowNumber++;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $rowNumber }}</td>
                        <td class="text-center capitalize">{{ $note->type }}</td>
                        <td class="uppercase">{{ $note->product_name }}</td>
                        <td class="text-center">{{ $note->size ?? '-' }}</td>
                        <td class="text-center uppercase">{{ $note->color ?? '-' }}</td>
                        <td class="text-right">Rp {{ number_format($note->original_price, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $note->discount_price ? 'Rp ' . number_format($note->discount_price, 0, ',', '.') : '-' }}</td>
                        <td class="text-center">{{ $note->quantity }}</td>
                        <td class="text-center capitalize">{{ $note->user->name ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    @empty
        <p>Tidak ada catatan pembelian ditemukan.</p>
    @endforelse
</body>
</html>
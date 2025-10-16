<?php

namespace App\Exports;

use App\Models\PurchaseNote;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchaseNotesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return PurchaseNote::with('user')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Jenis',
            'Nama Produk',
            'Ukuran',
            'Warna',
            'Harga Asli',
            'Harga Diskon',
            'Jumlah',
            'Dibuat Oleh',
            'Tanggal Dibuat',
        ];
    }

    public function map($note): array
    {
        static $rowNumber = 0;
        $rowNumber++;
        
        return [
            $rowNumber,
            $note->type,
            $note->product_name,
            $note->size ?? '-',
            $note->color ?? '-',
            'Rp ' . number_format($note->original_price, 0, ',', '.'),
            $note->discount_price ? 'Rp ' . number_format($note->discount_price, 0, ',', '.') : '-',
            $note->quantity,
            $note->user->name ?? '-',
            $note->created_at->format('d-m-Y'),
        ];
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\PurchaseNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PurchaseNotesExport;

class PurchaseNoteController extends Controller
{
    public function index()
    {
        $purchaseNotes = PurchaseNote::with('user')->latest()->get();
        $typeCounts = PurchaseNote::groupBy('type')
            ->selectRaw('type, count(*) as count')
            ->pluck('count', 'type')
            ->toArray();
        $productCounts = PurchaseNote::groupBy('product_name')
            ->selectRaw('product_name, count(*) as count')
            ->pluck('count', 'product_name')
            ->toArray();

        return view('purchase_notes.index', compact('purchaseNotes', 'typeCounts', 'productCounts'));
    }

    public function create()
    {
        return view('purchase_notes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'custom_type' => 'nullable|string|max:255|required_if:type,other',
            'product_name' => 'required|string|max:255',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'original_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $data = $request->all();
        if ($request->type === 'other') {
            $data['type'] = $request->custom_type;
        }
        $data['user_id'] = Auth::id();

        PurchaseNote::create($data);

        return redirect()->route('purchase_notes.index')->with('success', 'Catatan pembelian berhasil ditambahkan.');
    }

    public function edit(PurchaseNote $purchaseNote)
    {
        return view('purchase_notes.edit', compact('purchaseNote'));
    }

    public function update(Request $request, PurchaseNote $purchaseNote)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'custom_type' => 'nullable|string|max:255|required_if:type,other',
            'product_name' => 'required|string|max:255',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'original_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $data = $request->all();
        if ($request->type === 'other') {
            $data['type'] = $request->custom_type;
        }
        $data['user_id'] = Auth::id();

        $purchaseNote->update($data);

        return redirect()->route('purchase_notes.index')->with('success', 'Catatan pembelian berhasil diperbarui.');
    }

    public function destroy(PurchaseNote $purchaseNote)
    {
        $purchaseNote->delete();
        return redirect()->route('purchase_notes.index')->with('success', 'Catatan pembelian berhasil dihapus.');
    }

    public function exportPdf()
    {
        $purchaseNotes = PurchaseNote::with('user')->latest()->get();
        $pdf = Pdf::loadView('purchase_notes.pdf', compact('purchaseNotes'));
        return $pdf->download('catatan-pembelian-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new PurchaseNotesExport, 'catatan-pembelian-' . now()->format('Y-m-d') . '.xlsx');
    }
}
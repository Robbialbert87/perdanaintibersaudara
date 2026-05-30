<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Quotation::with('customer');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('nama_instansi', 'like', "%{$search}%");
                  });
        }

        $quotations = $query->latest()->paginate(10);
        return view('admin.quotations.index', compact('quotations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = \App\Models\Customer::orderBy('nama_instansi')->get();
        $products = \App\Models\Product::orderBy('name')->get();
        return view('admin.quotations.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'perihal' => 'required|array|min:1',
            'perihal.*' => 'required|string|max:255',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.nama_item' => 'nullable|string|max:255',
            'items.*.deskripsi' => 'required|string',
            'items.*.volume' => 'required|string|max:255',
            'items.*.harga_satuan' => 'required',
            'items.*.subtotal' => 'required',
        ]);

        $total = 0;
        $itemsData = [];
        $perihalArray = $request->perihal ?? [];
        $itemIndex = 0;
        foreach ($request->items as $item) {
            $harga = (float) str_replace(['.', ','], ['', '.'], $item['harga_satuan']);
            $subtotal = (float) str_replace(['.', ','], ['', '.'], $item['subtotal']);
            $total += $subtotal;
            
            $namaItem = !empty($item['nama_item']) ? $item['nama_item'] : ($perihalArray[$itemIndex] ?? null);

            $itemsData[] = [
                'nama_item' => $namaItem,
                'deskripsi' => $item['deskripsi'],
                'volume' => $item['volume'],
                'harga_satuan' => $harga,
                'subtotal' => $subtotal,
            ];
            $itemIndex++;
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $itemsData, $total) {
            $month = date('n', strtotime($request->tanggal));
            $year = date('Y', strtotime($request->tanggal));
            $romans = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
            $romanMonth = $romans[$month - 1];

            $lastQuotation = \App\Models\Quotation::whereYear('tanggal', $year)
                                ->whereMonth('tanggal', $month)
                                ->orderBy('id', 'desc')
                                ->first();

            $nextNumber = 1;
            if ($lastQuotation) {
                $parts = explode('/', $lastQuotation->nomor_surat);
                $nextNumber = intval($parts[0]) + 1;
            }

            $nomorSurat = sprintf('%03d/SP/CV.PIB-JMB/%s/%s', $nextNumber, $romanMonth, $year);

            $quotation = \App\Models\Quotation::create([
                'nomor_surat' => $nomorSurat,
                'tanggal' => $request->tanggal,
                'customer_id' => $request->customer_id,
                'perihal' => json_encode($request->perihal),
                'catatan' => $request->catatan,
                'status' => 'draft',
                'total' => 0, // will be calculated
            ]);

            foreach ($itemsData as $item) {
                $quotation->items()->create($item);
            }

            $quotation->update(['total' => $total]);
        });

        return redirect()->route('quotations.index')->with('success', 'Penawaran berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $quotation = \App\Models\Quotation::with(['customer', 'items.product'])->findOrFail($id);
        return view('admin.quotations.show', compact('quotation'));
    }

    /**
     * Export the specified resource to PDF.
     */
    public function exportPdf(string $id)
    {
        $quotation = \App\Models\Quotation::with(['customer', 'items.product'])->findOrFail($id);
        
        $pdf = app('dompdf.wrapper')->loadView('admin.quotations.pdf', compact('quotation'))
                ->setPaper(array(0, 0, 595.28, 935.43), 'portrait'); // F4 size
                
        // Generate a filename based on quotation number
        $filename = 'Penawaran_' . str_replace('/', '_', $quotation->nomor_surat) . '.pdf';
        
        return $pdf->stream($filename);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $quotation = \App\Models\Quotation::with('items')->findOrFail($id);
        $customers = \App\Models\Customer::orderBy('nama_instansi')->get();
        $products = \App\Models\Product::orderBy('name')->get();
        return view('admin.quotations.edit', compact('quotation', 'customers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'perihal' => 'required|array|min:1',
            'perihal.*' => 'required|string|max:255',
            'status' => 'required|in:draft,dikirim,deal,batal',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.nama_item' => 'nullable|string|max:255',
            'items.*.deskripsi' => 'required|string',
            'items.*.volume' => 'required|string|max:255',
            'items.*.harga_satuan' => 'required',
            'items.*.subtotal' => 'required',
        ]);

        $total = 0;
        $itemsData = [];
        $perihalArray = $request->perihal ?? [];
        $itemIndex = 0;
        foreach ($request->items as $item) {
            $harga = (float) str_replace(['.', ','], ['', '.'], $item['harga_satuan']);
            $subtotal = (float) str_replace(['.', ','], ['', '.'], $item['subtotal']);
            $total += $subtotal;
            
            $namaItem = !empty($item['nama_item']) ? $item['nama_item'] : ($perihalArray[$itemIndex] ?? null);

            $itemsData[] = [
                'nama_item' => $namaItem,
                'deskripsi' => $item['deskripsi'],
                'volume' => $item['volume'],
                'harga_satuan' => $harga,
                'subtotal' => $subtotal,
            ];
            $itemIndex++;
        }

        $quotation = \App\Models\Quotation::findOrFail($id);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $quotation, $itemsData, $total) {
            $quotation->update([
                'tanggal' => $request->tanggal,
                'customer_id' => $request->customer_id,
                'perihal' => json_encode($request->perihal),
                'status' => $request->status,
                'catatan' => $request->catatan,
            ]);

            // Hapus items lama
            $quotation->items()->delete();
            
            foreach ($itemsData as $item) {
                $quotation->items()->create($item);
            }

            $quotation->update(['total' => $total]);
        });

        return redirect()->route('quotations.index')->with('success', 'Penawaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $quotation = \App\Models\Quotation::findOrFail($id);
        $quotation->delete();
        return redirect()->route('quotations.index')->with('success', 'Penawaran berhasil dihapus.');
    }
}

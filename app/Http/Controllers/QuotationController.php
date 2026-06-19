<?php

namespace App\Http\Controllers;

use App\Helpers\QRCodeHelper;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Quotation::with('customer');

        if ($request->has('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->get('search'));
            $query->where('nomor_surat', 'like', "%{$search}%")
                ->orWhere('perihal', 'like', "%{$search}%")
                ->orWhereHas('customer', function ($q) use ($search) {
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
        $customers = Customer::orderBy('nama_instansi')->get();
        $products = Product::orderBy('name')->get();
        $services = Service::orderBy('title')->get();

        return view('admin.quotations.create', compact('customers', 'products', 'services'));
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
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.harga_satuan' => 'required',
        ]);

        $total = 0;
        $itemsData = [];
        $perihalArray = $request->perihal ?? [];
        $itemIndex = 0;
        foreach ($request->items as $item) {
            $harga = (float) str_replace(['.', ','], ['', '.'], $item['harga_satuan']);
            $volume = (float) str_replace(['.', ','], ['', '.'], $item['volume']);
            $subtotal = $volume * $harga;
            $total += $subtotal;

            $namaItem = ! empty($item['nama_item']) ? $item['nama_item'] : ($perihalArray[$itemIndex] ?? null);

            $itemsData[] = [
                'nama_item' => $namaItem,
                'deskripsi' => $item['deskripsi'],
                'volume' => $item['volume'],
                'satuan' => $item['satuan'] ?? null,
                'harga_satuan' => $harga,
                'subtotal' => $subtotal,
            ];
            $itemIndex++;
        }

        DB::transaction(function () use ($request, $itemsData, $total) {
            $month = date('n', strtotime($request->tanggal));
            $year = date('Y', strtotime($request->tanggal));
            $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $romanMonth = $romans[$month - 1];

            $lastQuotation = Quotation::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = 101;
            if ($lastQuotation) {
                $parts = explode('/', $lastQuotation->nomor_surat);
                $lastNumber = intval($parts[0]);
                $nextNumber = max(101, $lastNumber + 1);
            }

            $nomorSurat = sprintf('%03d/SP/PIB-JMB/%s/%s', $nextNumber, $romanMonth, $year);

            $quotation = Quotation::create([
                'nomor_surat' => $nomorSurat,
                'tanggal' => $request->tanggal,
                'customer_id' => $request->customer_id,
                'perihal' => json_encode($request->perihal),
                'catatan' => $request->catatan,
                'tampilkan_gambar' => $request->boolean('tampilkan_gambar'),
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
        $quotation = Quotation::with(['customer', 'items.product'])->findOrFail($id);

        return view('admin.quotations.show', compact('quotation'));
    }

    /**
     * Export the specified resource to PDF.
     */
    public function exportPdf(string $id)
    {
        $quotation = Quotation::with(['customer', 'items.product'])->findOrFail($id);

        if (empty($quotation->verify_token)) {
            $quotation->update(['verify_token' => (string) Str::uuid()]);
            $quotation->refresh();
        }

        $verifyUrl = route('verify.quotation', $quotation->verify_token);
        $qrCode = QRCodeHelper::generate($verifyUrl, 150);

        // Collect images for each perihal item
        $products = Product::select('name', 'images', 'active_images')->get()->keyBy('name');
        $services = Service::select('title', 'image', 'images', 'active_images')->get()->keyBy('title');
        $perihalImages = [];
        $perihalArray = is_array($quotation->perihal) ? $quotation->perihal : (json_decode($quotation->perihal, true) ?? []);
        foreach ($perihalArray as $name) {
            $localPath = null;
            if ($product = $products->get($name)) {
                $firstImg = $product->active_images[0] ?? $product->images[0] ?? null;
                if ($firstImg) $localPath = Storage::disk('public')->path($firstImg);
            } elseif ($service = $services->get($name)) {
                $firstImg = $service->active_images[0] ?? $service->images[0] ?? $service->image ?? null;
                if ($firstImg) $localPath = Storage::disk('public')->path($firstImg);
            }
            $perihalImages[] = ['name' => $name, 'path' => $localPath && file_exists($localPath) ? $localPath : null];
        }

        $pdf = app('dompdf.wrapper')->loadView('admin.quotations.pdf', compact('quotation', 'qrCode', 'perihalImages'))
            ->setPaper([0, 0, 595.28, 935.43], 'portrait'); // F4 size

        // Generate a filename based on quotation number
        $filename = 'Penawaran_'.str_replace('/', '_', $quotation->nomor_surat).'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $quotation = Quotation::with('items')->findOrFail($id);
        $customers = Customer::orderBy('nama_instansi')->get();
        $products = Product::orderBy('name')->get();
        $services = Service::orderBy('title')->get();

        return view('admin.quotations.edit', compact('quotation', 'customers', 'products', 'services'));
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
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.harga_satuan' => 'required',
        ]);

        $total = 0;
        $itemsData = [];
        $perihalArray = $request->perihal ?? [];
        $itemIndex = 0;
        foreach ($request->items as $item) {
            $harga = (float) str_replace(['.', ','], ['', '.'], $item['harga_satuan']);
            $volume = (float) str_replace(['.', ','], ['', '.'], $item['volume']);
            $subtotal = $volume * $harga;
            $total += $subtotal;

            $namaItem = ! empty($item['nama_item']) ? $item['nama_item'] : ($perihalArray[$itemIndex] ?? null);

            $itemsData[] = [
                'nama_item' => $namaItem,
                'deskripsi' => $item['deskripsi'],
                'volume' => $item['volume'],
                'satuan' => $item['satuan'] ?? null,
                'harga_satuan' => $harga,
                'subtotal' => $subtotal,
            ];
            $itemIndex++;
        }

        $quotation = Quotation::findOrFail($id);

        DB::transaction(function () use ($request, $quotation, $itemsData, $total) {
            $quotation->update([
                'tanggal' => $request->tanggal,
                'customer_id' => $request->customer_id,
                'perihal' => json_encode($request->perihal),
                'status' => $request->status,
                'catatan' => $request->catatan,
                'tampilkan_gambar' => $request->boolean('tampilkan_gambar'),
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
        $quotation = Quotation::findOrFail($id);
        $quotation->delete();

        return redirect()->route('quotations.index')->with('success', 'Penawaran berhasil dihapus.');
    }
}

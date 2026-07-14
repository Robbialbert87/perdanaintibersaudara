<?php

namespace App\Http\Controllers;

use App\Helpers\QRCodeHelper;
use App\Models\Customer;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with('customer');

        if ($request->has('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->get('search'));
            $query->where('nomor_surat', 'like', "%{$search}%")
                ->orWhere('perihal', 'like', "%{$search}%")
                ->orWhereHas('customer', function ($q) use ($search) {
                    $q->where('nama_instansi', 'like', "%{$search}%");
                });
        }

        $purchaseOrders = $query->latest()->paginate(10);

        return view('admin.purchase-orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $customers = Customer::orderBy('nama_instansi')->get();
        $products = Product::orderBy('name')->get();
        $services = Service::orderBy('title')->get();

        return view('admin.purchase-orders.create', compact('customers', 'products', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'perihal' => 'required|array|min:1',
            'perihal.*' => 'required|string|max:255',
            'perihal_surat' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'kata_pengantar' => 'nullable|string',
            'kata_penutup' => 'nullable|string',
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
                'tampilkan_label' => $item['tampilkan_label'] === '1',
            ];
            $itemIndex++;
        }

        DB::transaction(function () use ($request, $itemsData, $total) {
            $month = date('n', strtotime($request->tanggal));
            $year = date('Y', strtotime($request->tanggal));
            $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $romanMonth = $romans[$month - 1];

            $lastPO = PurchaseOrder::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = 101;
            if ($lastPO) {
                $parts = explode('/', $lastPO->nomor_surat);
                $lastNumber = intval($parts[0]);
                $nextNumber = max(101, $lastNumber + 1);
            }

            $nomorSurat = sprintf('%03d/PO/PIB-JMB/%s/%s', $nextNumber, $romanMonth, $year);

            $selectedImages = $request->input('selected_images', '');
            if (is_string($selectedImages)) {
                $selectedImages = json_decode($selectedImages, true) ?? [];
            }

            $purchaseOrder = PurchaseOrder::create([
                'nomor_surat' => $nomorSurat,
                'tanggal' => $request->tanggal,
                'customer_id' => $request->customer_id,
                'perihal' => json_encode($request->perihal),
                'perihal_surat' => $request->perihal_surat,
                'catatan' => $request->filled('catatan') ? trim($request->catatan) : null,
                'kata_pengantar' => $request->kata_pengantar,
                'kata_penutup' => $request->kata_penutup,
                'tampilkan_gambar' => !empty($selectedImages),
                'selected_images' => $selectedImages,
                'status' => 'draft',
                'total' => 0,
            ]);

            foreach ($itemsData as $item) {
                $purchaseOrder->items()->create($item);
            }

            $purchaseOrder->update(['total' => $total]);
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dibuat.');
    }

    public function show(string $id)
    {
        $purchaseOrder = PurchaseOrder::with(['customer', 'items.product'])->findOrFail($id);

        return view('admin.purchase-orders.show', compact('purchaseOrder'));
    }

    public function exportPdf(string $id)
    {
        $purchaseOrder = PurchaseOrder::with(['customer', 'items.product'])->findOrFail($id);

        if (empty($purchaseOrder->verify_token)) {
            $purchaseOrder->update(['verify_token' => (string) Str::uuid()]);
            $purchaseOrder->refresh();
        }

        $verifyUrl = route('verify.purchase_order', $purchaseOrder->verify_token);
        $qrCode = QRCodeHelper::generate($verifyUrl, 150);

        $products = Product::select('name', 'images', 'active_images')->get()->keyBy('name');
        $services = Service::select('title', 'image', 'images', 'active_images')->get()->keyBy('title');
        $perihalImages = [];

        $selectedImages = $purchaseOrder->selected_images;
        if (is_string($selectedImages)) {
            $selectedImages = json_decode($selectedImages, true) ?? [];
        } elseif (!is_array($selectedImages)) {
            $selectedImages = [];
        }

        if (!empty($selectedImages)) {
            foreach ($selectedImages as $name => $images) {
                foreach ($images as $imgPath) {
                    $localPath = Storage::disk('public')->path($imgPath);
                    $perihalImages[] = [
                        'name' => $name,
                        'path' => file_exists($localPath) ? $localPath : null,
                    ];
                }
            }
        } else {
            $perihalArray = is_array($purchaseOrder->perihal) ? $purchaseOrder->perihal : (json_decode($purchaseOrder->perihal, true) ?? []);
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
        }

        $pdf = app('dompdf.wrapper')->loadView('admin.purchase-orders.pdf', compact('purchaseOrder', 'qrCode', 'perihalImages'))
            ->setPaper([0, 0, 595.28, 935.43], 'portrait');

        $filename = 'Purchase_Order_'.str_replace('/', '_', $purchaseOrder->nomor_surat).'.pdf';

        return $pdf->download($filename);
    }

    public function edit(string $id)
    {
        $purchaseOrder = PurchaseOrder::with('items')->findOrFail($id);
        $customers = Customer::orderBy('nama_instansi')->get();
        $products = Product::orderBy('name')->get();
        $services = Service::orderBy('title')->get();

        return view('admin.purchase-orders.edit', compact('purchaseOrder', 'customers', 'products', 'services'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'perihal' => 'required|array|min:1',
            'perihal.*' => 'required|string|max:255',
            'status' => 'required|in:draft,dikirim,dikonfirmasi,batal',
            'perihal_surat' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'kata_pengantar' => 'nullable|string',
            'kata_penutup' => 'nullable|string',
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
                'tampilkan_label' => $item['tampilkan_label'] === '1',
            ];
            $itemIndex++;
        }

        $purchaseOrder = PurchaseOrder::findOrFail($id);

        DB::transaction(function () use ($request, $purchaseOrder, $itemsData, $total) {
            $selectedImages = $request->input('selected_images', '');
            if (is_string($selectedImages)) {
                $selectedImages = json_decode($selectedImages, true) ?? [];
            }

            $purchaseOrder->update([
                'tanggal' => $request->tanggal,
                'customer_id' => $request->customer_id,
                'perihal' => json_encode($request->perihal),
                'perihal_surat' => $request->perihal_surat,
                'status' => $request->status,
                'catatan' => $request->filled('catatan') ? trim($request->catatan) : null,
                'kata_pengantar' => $request->kata_pengantar,
                'kata_penutup' => $request->kata_penutup,
                'tampilkan_gambar' => !empty($selectedImages),
                'selected_images' => $selectedImages,
            ]);

            $purchaseOrder->items()->delete();

            foreach ($itemsData as $item) {
                $purchaseOrder->items()->create($item);
            }

            $purchaseOrder->update(['total' => $total]);
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->delete();

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dihapus.');
    }
}

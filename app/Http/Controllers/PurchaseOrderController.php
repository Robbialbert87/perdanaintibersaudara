<?php

namespace App\Http\Controllers;

use App\Helpers\QRCodeHelper;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::query();

        if ($request->has('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('vendor', 'like', "%{$search}%")
                  ->orWhere('buyer_name', 'like', "%{$search}%")
                  ->orWhere('po_number', 'like', "%{$search}%");
            });
        }

        $purchaseOrders = $query->latest()->paginate(10);

        return view('admin.purchase-orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        return view('admin.purchase-orders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'po_date' => 'required|date',
            'vendor' => 'required|string|max:255',
            'vendor_address' => 'nullable|string',
            'vendor_cp' => 'nullable|string|max:255',
            'vendor_phone' => 'nullable|string|max:50',
            'buyer_name' => 'required|string|max:255',
            'buyer_address' => 'nullable|string',
            'buyer_cp' => 'nullable|string|max:255',
            'buyer_phone' => 'nullable|string|max:50',
            'shipping_name' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string',
            'shipping_cp' => 'nullable|string|max:255',
            'shipping_phone' => 'nullable|string|max:50',
            'discount' => 'nullable',
            'ppn' => 'nullable',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'nullable|string|max:255',
            'items.*.deskripsi' => 'required|string',
            'items.*.qty' => 'required',
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.price' => 'required',
            'items.*.dp_persentase' => 'nullable|numeric|min:0|max:100',
        ]);

        $total = 0;
        $totalDp = 0;
        $itemsData = [];

        foreach ($request->items as $item) {
            $qty = (float) str_replace(['.', ','], ['', '.'], $item['qty']);
            $harga = (float) str_replace(['.', ','], ['', '.'], $item['price']);
            $subtotal = $qty * $harga;
            $dpPersentase = (float) ($item['dp_persentase'] ?? 0);
            $dpNominal = $subtotal * ($dpPersentase / 100);
            $total += $subtotal;
            $totalDp += $dpNominal;

            $itemsData[] = [
                'product_name' => $item['product_name'] ?? null,
                'deskripsi' => $item['deskripsi'],
                'volume' => $qty,
                'satuan' => $item['satuan'] ?? null,
                'harga_satuan' => $harga,
                'subtotal' => $subtotal,
                'dp_persentase' => $dpPersentase,
                'dp_nominal' => $dpNominal,
                'tampilkan_label' => true,
            ];
        }

        $discount = (float) str_replace(['.', ','], ['', '.'], $request->discount ?? 0);
        $ppn = (float) str_replace(['.', ','], ['', '.'], $request->ppn ?? 0);
        $grandTotal = $total - $discount + $ppn;

        DB::transaction(function () use ($request, $itemsData, $total, $totalDp, $discount, $ppn, $grandTotal) {
            $month = date('n', strtotime($request->po_date));
            $year = date('Y', strtotime($request->po_date));
            $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $romanMonth = $romans[$month - 1];

            $lastPO = PurchaseOrder::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = 101;
            if ($lastPO && $lastPO->nomor_surat) {
                $parts = explode('/', $lastPO->nomor_surat);
                $lastNumber = intval($parts[0]);
                $nextNumber = max(101, $lastNumber + 1);
            }

            $nomorSurat = sprintf('%03d/PO/PIB-JMB/%s/%s', $nextNumber, $romanMonth, $year);

            $purchaseOrder = PurchaseOrder::create([
                'nomor_surat' => $nomorSurat,
                'po_number' => $nomorSurat,
                'tanggal' => $request->po_date,
                'po_date' => $request->po_date,
                'vendor' => $request->vendor,
                'vendor_address' => $request->vendor_address,
                'vendor_cp' => $request->vendor_cp,
                'vendor_phone' => $request->vendor_phone,
                'buyer_name' => $request->buyer_name,
                'buyer_address' => $request->buyer_address,
                'buyer_cp' => $request->buyer_cp,
                'buyer_phone' => $request->buyer_phone,
                'shipping_name' => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_cp' => $request->shipping_cp,
                'shipping_phone' => $request->shipping_phone,
                'discount' => $discount,
                'ppn' => $ppn,
                'total' => $total,
                'total_dp' => $totalDp,
                'grand_total' => $grandTotal,
                'status' => 'draft',
                'catatan' => $request->filled('catatan') ? trim($request->catatan) : null,
            ]);

            foreach ($itemsData as $item) {
                $purchaseOrder->items()->create($item);
            }
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dibuat.');
    }

    public function show(string $id)
    {
        $purchaseOrder = PurchaseOrder::with(['items'])->findOrFail($id);

        return view('admin.purchase-orders.show', compact('purchaseOrder'));
    }

    public function exportPdf(string $id)
    {
        $purchaseOrder = PurchaseOrder::with(['items'])->findOrFail($id);

        if (empty($purchaseOrder->verify_token)) {
            $purchaseOrder->update(['verify_token' => (string) Str::uuid()]);
            $purchaseOrder->refresh();
        }

        $verifyUrl = route('verify.purchase_order', $purchaseOrder->verify_token);
        $qrCode = QRCodeHelper::generate($verifyUrl, 150);

        $pdf = app('dompdf.wrapper')->loadView('admin.purchase-orders.pdf', compact('purchaseOrder', 'qrCode'))
            ->setPaper([0, 0, 595.28, 935.43], 'portrait');

        $filename = 'Purchase_Order_'.str_replace('/', '_', $purchaseOrder->nomor_surat).'.pdf';

        return $pdf->download($filename);
    }

    public function previewPdf(string $id)
    {
        $purchaseOrder = PurchaseOrder::with(['items'])->findOrFail($id);

        if (empty($purchaseOrder->verify_token)) {
            $purchaseOrder->update(['verify_token' => (string) Str::uuid()]);
            $purchaseOrder->refresh();
        }

        $verifyUrl = route('verify.purchase_order', $purchaseOrder->verify_token);
        $qrCode = QRCodeHelper::generate($verifyUrl, 150);

        $pdf = app('dompdf.wrapper')->loadView('admin.purchase-orders.pdf', compact('purchaseOrder', 'qrCode'))
            ->setPaper([0, 0, 595.28, 935.43], 'portrait');

        return $pdf->inline();
    }

    public function print(string $id)
    {
        $purchaseOrder = PurchaseOrder::with(['items'])->findOrFail($id);

        if (empty($purchaseOrder->verify_token)) {
            $purchaseOrder->update(['verify_token' => (string) Str::uuid()]);
            $purchaseOrder->refresh();
        }

        $verifyUrl = route('verify.purchase_order', $purchaseOrder->verify_token);
        $qrCode = QRCodeHelper::generate($verifyUrl, 150);

        return view('admin.purchase-orders.pdf', compact('purchaseOrder', 'qrCode'));
    }

    public function edit(string $id)
    {
        $purchaseOrder = PurchaseOrder::with('items')->findOrFail($id);

        return view('admin.purchase-orders.edit', compact('purchaseOrder'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'po_date' => 'required|date',
            'vendor' => 'required|string|max:255',
            'vendor_address' => 'nullable|string',
            'vendor_cp' => 'nullable|string|max:255',
            'vendor_phone' => 'nullable|string|max:50',
            'buyer_name' => 'required|string|max:255',
            'buyer_address' => 'nullable|string',
            'buyer_cp' => 'nullable|string|max:255',
            'buyer_phone' => 'nullable|string|max:50',
            'shipping_name' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string',
            'shipping_cp' => 'nullable|string|max:255',
            'shipping_phone' => 'nullable|string|max:50',
            'status' => 'required|in:draft,dikirim,dikonfirmasi,batal',
            'discount' => 'nullable',
            'ppn' => 'nullable',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'nullable|string|max:255',
            'items.*.deskripsi' => 'required|string',
            'items.*.qty' => 'required',
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.price' => 'required',
            'items.*.dp_persentase' => 'nullable|numeric|min:0|max:100',
        ]);

        $total = 0;
        $totalDp = 0;
        $itemsData = [];

        foreach ($request->items as $item) {
            $qty = (float) str_replace(['.', ','], ['', '.'], $item['qty']);
            $harga = (float) str_replace(['.', ','], ['', '.'], $item['price']);
            $subtotal = $qty * $harga;
            $dpPersentase = (float) ($item['dp_persentase'] ?? 0);
            $dpNominal = $subtotal * ($dpPersentase / 100);
            $total += $subtotal;
            $totalDp += $dpNominal;

            $itemsData[] = [
                'product_name' => $item['product_name'] ?? null,
                'deskripsi' => $item['deskripsi'],
                'volume' => $qty,
                'satuan' => $item['satuan'] ?? null,
                'harga_satuan' => $harga,
                'subtotal' => $subtotal,
                'dp_persentase' => $dpPersentase,
                'dp_nominal' => $dpNominal,
                'tampilkan_label' => true,
            ];
        }

        $discount = (float) str_replace(['.', ','], ['', '.'], $request->discount ?? 0);
        $ppn = (float) str_replace(['.', ','], ['', '.'], $request->ppn ?? 0);
        $grandTotal = $total - $discount + $ppn;

        $purchaseOrder = PurchaseOrder::findOrFail($id);

        DB::transaction(function () use ($request, $purchaseOrder, $itemsData, $total, $totalDp, $discount, $ppn, $grandTotal) {
            $purchaseOrder->update([
                'tanggal' => $request->po_date,
                'po_date' => $request->po_date,
                'vendor' => $request->vendor,
                'vendor_address' => $request->vendor_address,
                'vendor_cp' => $request->vendor_cp,
                'vendor_phone' => $request->vendor_phone,
                'buyer_name' => $request->buyer_name,
                'buyer_address' => $request->buyer_address,
                'buyer_cp' => $request->buyer_cp,
                'buyer_phone' => $request->buyer_phone,
                'shipping_name' => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_cp' => $request->shipping_cp,
                'shipping_phone' => $request->shipping_phone,
                'status' => $request->status,
                'discount' => $discount,
                'ppn' => $ppn,
                'total' => $total,
                'total_dp' => $totalDp,
                'grand_total' => $grandTotal,
                'catatan' => $request->filled('catatan') ? trim($request->catatan) : null,
            ]);

            $purchaseOrder->items()->delete();

            foreach ($itemsData as $item) {
                $purchaseOrder->items()->create($item);
            }
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

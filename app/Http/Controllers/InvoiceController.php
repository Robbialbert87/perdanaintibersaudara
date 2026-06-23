<?php

namespace App\Http\Controllers;

use App\Helpers\QRCodeHelper;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('customer', 'items');

        if ($request->has('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->get('search'));
            $query->where('nomor_invoice', 'like', "%{$search}%")
                ->orWhere('perihal', 'like', "%{$search}%")
                ->orWhereHas('customer', function ($q) use ($search) {
                    $q->where('nama_instansi', 'like', "%{$search}%");
                });
        }

        $invoices = $query->latest()->paginate(10);

        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::orderBy('nama_instansi')->get();
        $products = Product::orderBy('name')->get();
        $services = Service::orderBy('title')->get();

        return view('admin.invoices.create', compact('customers', 'products', 'services'));
    }

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
            'items.*.tanggal_kegiatan' => 'nullable|date',
            'items.*.volume' => 'required|string|max:255',
            'items.*.satuan' => 'nullable|string|max:50',
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

            $namaItem = ! empty($item['nama_item']) ? $item['nama_item'] : ($perihalArray[$itemIndex] ?? null);

            $itemsData[] = [
                'nama_item' => $namaItem,
                'deskripsi' => $item['deskripsi'],
                'tanggal_kegiatan' => $item['tanggal_kegiatan'] ?? null,
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

            $lastInvoice = Invoice::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = 1;
            if ($lastInvoice) {
                $parts = explode('/', $lastInvoice->nomor_invoice);
                $nextNumber = intval($parts[0]) + 1;
            }

            $nomorInvoice = sprintf('%03d/INV/PIB-JMB/%s/%s', $nextNumber, $romanMonth, $year);

            $invoice = Invoice::create([
                'nomor_invoice' => $nomorInvoice,
                'tanggal' => $request->tanggal,
                'customer_id' => $request->customer_id,
                'perihal' => $request->perihal,
                'catatan' => $request->filled('catatan') ? trim($request->catatan) : null,
                'status' => 'draft',
                'total' => 0,
            ]);

            foreach ($itemsData as $item) {
                $invoice->items()->create($item);
            }

            $invoice->update(['total' => $total]);
        });

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil dibuat.');
    }

    public function show(string $id)
    {
        $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($id);

        return view('admin.invoices.show', compact('invoice'));
    }

    public function edit(string $id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $customers = Customer::orderBy('nama_instansi')->get();
        $products = Product::orderBy('name')->get();
        $services = Service::orderBy('title')->get();

        return view('admin.invoices.edit', compact('invoice', 'customers', 'products', 'services'));
    }

    public function update(Request $request, string $id)
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
            'items.*.tanggal_kegiatan' => 'nullable|date',
            'items.*.volume' => 'required|string|max:255',
            'items.*.satuan' => 'nullable|string|max:50',
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

            $namaItem = ! empty($item['nama_item']) ? $item['nama_item'] : ($perihalArray[$itemIndex] ?? null);

            $itemsData[] = [
                'nama_item' => $namaItem,
                'deskripsi' => $item['deskripsi'],
                'tanggal_kegiatan' => $item['tanggal_kegiatan'] ?? null,
                'volume' => $item['volume'],
                'satuan' => $item['satuan'] ?? null,
                'harga_satuan' => $harga,
                'subtotal' => $subtotal,
            ];
            $itemIndex++;
        }

        $invoice = Invoice::findOrFail($id);

        DB::transaction(function () use ($request, $invoice, $itemsData, $total) {
            $invoice->update([
                'tanggal' => $request->tanggal,
                'customer_id' => $request->customer_id,
                'perihal' => $request->perihal,
                'catatan' => $request->filled('catatan') ? trim($request->catatan) : null,
            ]);

            $invoice->items()->delete();

            foreach ($itemsData as $item) {
                $invoice->items()->create($item);
            }

            $invoice->update(['total' => $total]);
        });

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil diperbarui.');
    }

    public function aiGenerate(Request $request)
    {
        $request->validate(['prompt' => 'required|string']);

        $apiKey = config('services.gemini.api_key');
        if (!$apiKey) {
            return response()->json(['error' => 'GEMINI_API_KEY belum dikonfigurasi.'], 500);
        }

        $systemPrompt = <<<PROMPT
Anda adalah asisten yang membantu mengisi form invoice.
Dari teks berikut, ekstrak informasi customer, perihal, item (nama_item, deskripsi, volume, satuan, harga_satuan), dan catatan.
Kembalikan HANYA JSON tanpa markdown atau teks lain.

Format JSON:
{
  "customer_name": "nama instansi atau kosong",
  "perihal": ["nama produk/jasa"],
  "items": [
    {
      "nama_item": "nama item",
      "deskripsi": "deskripsi",
      "volume": "1",
      "satuan": "Unit",
      "harga_satuan": 0
    }
  ],
  "catatan": ""
}

Contoh:
Input: "MCU 50 orang harga 100.000 untuk RSUD Sultan Thaha"
Output: {"customer_name":"RSUD Sultan Thaha Saifuddin","perihal":["MCU"],"items":[{"nama_item":"MCU","deskripsi":"Medical Check Up 50 orang","volume":"50","satuan":"Orang","harga_satuan":100000}],"catatan":""}
PROMPT;

        $response = Http::timeout(30)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent?key={$apiKey}",
            [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt . "\nInput: " . $request->prompt . "\nOutput:"]
                        ]
                    ]
                ]
            ]
        );

        if (!$response->successful()) {
            return response()->json(['error' => 'Gagal memproses AI: ' . $response->body()], 500);
        }

        $body = $response->json();
        $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
        $text = trim(preg_replace('/^```(?:json)?\s*|\s*```$/', '', $text));

        $data = json_decode($text, true);
        if (!$data || !isset($data['items'])) {
            return response()->json(['error' => 'AI tidak dapat memahami prompt. Coba lebih detail.'], 422);
        }

        // Cari customer
        $customer = null;
        $foundCustomers = [];
        if (!empty($data['customer_name'])) {
            $foundCustomers = Customer::where('nama_instansi', 'like', '%' . $data['customer_name'] . '%')->get();
            $customer = $foundCustomers->first();
        }

        // Hitung total
        $total = 0;
        foreach ($data['items'] as &$item) {
            $harga = (float) ($item['harga_satuan'] ?? 0);
            $volume = (float) ($item['volume'] ?? 1);
            $subtotal = $volume * $harga;
            $item['subtotal'] = $subtotal;
            $total += $subtotal;
        }

        // Format perihal
        $perihal = $data['perihal'] ?? [];

        $previewData = [
            'tanggal' => now()->format('Y-m-d'),
            'customer' => $customer,
            'customer_name' => $data['customer_name'] ?? '',
            'foundCustomers' => $foundCustomers,
            'perihal' => $perihal,
            'items' => $data['items'],
            'total' => $total,
            'catatan' => $data['catatan'] ?? '',
        ];

        $html = view('admin.invoices._preview', $previewData)->render();

        return response()->json([
            'html' => $html,
            'data' => [
                'customer_id' => $customer?->id,
                'customer_name' => $data['customer_name'] ?? '',
                'perihal' => $perihal,
                'items' => $data['items'],
                'total' => $total,
                'catatan' => $data['catatan'] ?? '',
            ],
        ]);
    }

    public function aiStore(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'perihal' => 'required|array|min:1',
            'perihal.*' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.nama_item' => 'nullable|string|max:255',
            'items.*.deskripsi' => 'required|string',
            'items.*.volume' => 'required|string|max:255',
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.harga_satuan' => 'required',
            'items.*.subtotal' => 'required',
            'catatan' => 'nullable|string',
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
                'tanggal_kegiatan' => $item['tanggal_kegiatan'] ?? null,
                'volume' => $item['volume'],
                'satuan' => $item['satuan'] ?? null,
                'harga_satuan' => $harga,
                'subtotal' => $subtotal,
            ];
            $itemIndex++;
        }

        DB::transaction(function () use ($request, $itemsData, $total) {
            $month = date('n');
            $year = date('Y');
            $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $romanMonth = $romans[$month - 1];

            $lastInvoice = Invoice::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = 1;
            if ($lastInvoice) {
                $parts = explode('/', $lastInvoice->nomor_invoice);
                $nextNumber = intval($parts[0]) + 1;
            }

            $nomorInvoice = sprintf('%03d/INV/PIB-JMB/%s/%s', $nextNumber, $romanMonth, $year);

            $invoice = Invoice::create([
                'nomor_invoice' => $nomorInvoice,
                'tanggal' => now()->format('Y-m-d'),
                'customer_id' => $request->customer_id,
                'perihal' => $request->perihal,
                'catatan' => $request->filled('catatan') ? trim($request->catatan) : null,
                'status' => 'draft',
                'total' => 0,
            ]);

            foreach ($itemsData as $item) {
                $invoice->items()->create($item);
            }

            $invoice->update(['total' => $total]);
        });

        return response()->json(['success' => true, 'redirect' => route('invoices.index')]);
    }

    public function destroy(string $id)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil dihapus.');
    }

    public function exportPdf(string $id)
    {
        $invoice = Invoice::with(['customer', 'items'])->findOrFail($id);

        if (empty($invoice->verify_token)) {
            $invoice->update(['verify_token' => (string) Str::uuid()]);
            $invoice->refresh();
        }

        $verifyUrl = route('verify.invoice', $invoice->verify_token);
        $qrCode = QRCodeHelper::generate($verifyUrl, 200);

        $pdf = app('dompdf.wrapper')->loadView('admin.invoices.pdf', compact('invoice', 'qrCode'))
            ->setPaper([0, 0, 595.28, 935.43], 'portrait');

        $filename = 'Invoice_'.str_replace('/', '_', $invoice->nomor_invoice).'.pdf';

        return $pdf->download($filename);
    }

    public function markAsPaid(Request $request, string $id)
    {
        $invoice = Invoice::findOrFail($id);

        $request->validate([
            'tanggal_bayar' => 'required|date',
            'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');

        $invoice->update([
            'status' => 'dibayar',
            'tanggal_bayar' => $request->tanggal_bayar,
            'bukti_bayar' => $path,
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil ditandai sebagai Lunas.');
    }
}

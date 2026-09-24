<?php

namespace App\Http\Controllers;

use App\Helpers\QRCodeHelper;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
            'items.*.diskon' => 'nullable|numeric|min:0|max:100',
        ]);

        $total = 0;
        $itemsData = [];
        $perihalArray = $request->perihal ?? [];
        $itemIndex = 0;
        foreach ($request->items as $item) {
            $harga = (float) str_replace(['.', ','], ['', '.'], $item['harga_satuan']);
            $volume = (float) str_replace(['.', ','], ['', '.'], $item['volume']);
            $diskon = !empty($item['diskon']) ? (float) $item['diskon'] : 0;
            if ($diskon > 0) {
                $subtotal = $volume * $harga * (100 - $diskon) / 100;
            } else {
                $subtotal = $volume * $harga;
            }
            $total += $subtotal;

            $namaItem = ! empty($item['nama_item']) ? $item['nama_item'] : ($perihalArray[$itemIndex] ?? null);

            $itemsData[] = [
                'nama_item' => $namaItem,
                'deskripsi' => $item['deskripsi'],
                'volume' => $item['volume'],
                'satuan' => $item['satuan'] ?? null,
                'harga_satuan' => $harga,
                'diskon' => $diskon > 0 ? $diskon : null,
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

            $lastQuotation = Quotation::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = $month * 100 + 1;
            if ($lastQuotation) {
                $parts = explode('/', $lastQuotation->nomor_surat);
                $lastNumber = intval($parts[0]);
                $nextNumber = max($month * 100 + 1, $lastNumber + 1);
            }

            $nomorSurat = sprintf('%03d/SP/PIB-JMB/%s/%s', $nextNumber, $romanMonth, $year);

            $selectedImages = $request->input('selected_images', '');
            if (is_string($selectedImages)) {
                $selectedImages = json_decode($selectedImages, true) ?? [];
            }

            $quotation = Quotation::create([
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

        $selectedImages = $quotation->selected_images;
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
        }

        // Determine perihal type label
        $perihalArray = is_array($quotation->perihal) ? $quotation->perihal : (json_decode($quotation->perihal, true) ?? [$quotation->perihal]);
        $hasProduct = false;
        $hasService = false;
        foreach ($perihalArray as $name) {
            if ($products->has($name)) $hasProduct = true;
            if ($services->has($name)) $hasService = true;
        }
        $perihalLabel = 'produk';
        if ($hasProduct && $hasService) {
            $perihalLabel = 'produk dan layanan';
        } elseif ($hasService) {
            $perihalLabel = 'layanan';
        }

        $pdf = app('dompdf.wrapper')->loadView('admin.quotations.pdf', compact('quotation', 'qrCode', 'perihalImages', 'perihalLabel'))
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
            'items.*.diskon' => 'nullable|numeric|min:0|max:100',
        ]);

        $total = 0;
        $itemsData = [];
        $perihalArray = $request->perihal ?? [];
        $itemIndex = 0;
        foreach ($request->items as $item) {
            $harga = (float) str_replace(['.', ','], ['', '.'], $item['harga_satuan']);
            $volume = (float) str_replace(['.', ','], ['', '.'], $item['volume']);
            $diskon = !empty($item['diskon']) ? (float) $item['diskon'] : 0;
            if ($diskon > 0) {
                $subtotal = $volume * $harga * (100 - $diskon) / 100;
            } else {
                $subtotal = $volume * $harga;
            }
            $total += $subtotal;

            $namaItem = ! empty($item['nama_item']) ? $item['nama_item'] : ($perihalArray[$itemIndex] ?? null);

            $itemsData[] = [
                'nama_item' => $namaItem,
                'deskripsi' => $item['deskripsi'],
                'volume' => $item['volume'],
                'satuan' => $item['satuan'] ?? null,
                'harga_satuan' => $harga,
                'diskon' => $diskon > 0 ? $diskon : null,
                'subtotal' => $subtotal,
                'tampilkan_label' => $item['tampilkan_label'] === '1',
            ];
            $itemIndex++;
        }

        $quotation = Quotation::findOrFail($id);

        DB::transaction(function () use ($request, $quotation, $itemsData, $total) {
            $selectedImages = $request->input('selected_images', '');
            if (is_string($selectedImages)) {
                $selectedImages = json_decode($selectedImages, true) ?? [];
            }

            $quotation->update([
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
     * Generate quotation draft via AI.
     */
    public function aiGenerate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
            'provider' => 'nullable|string|in:gemini,openrouter',
        ]);

        $provider = $request->provider ?? config('services.ai.provider', 'gemini');

        $customerNames = Customer::pluck('nama_instansi')->toArray();
        $customerList = implode(', ', array_map(fn($n) => '"' . $n . '"', $customerNames));

        $systemPrompt = <<<PROMPT
Anda adalah asisten yang membantu mengisi form surat penawaran (quotation).
Dari teks berikut, ekstrak informasi customer, daftar perihal (nama kelompok pekerjaan/barang), item (nama_item, deskripsi, volume, satuan, harga_satuan, diskon), perihal_surat, dan catatan.
perihal_surat adalah judul singkat surat penawaran (boleh kosong), contoh "Pengadaan Alat Kesehatan".
perihal adalah daftar nama kelompok/kategori pekerjaan atau barang yang ditawarkan (wajib minimal 1).
catatan hanya diisi jika user secara eksplisit menyebutkan catatan atau keterangan tambahan.
diskon dalam angka persen (contoh: diskon 5% menjadi 5), default 0.
Kembalikan HANYA JSON tanpa markdown atau teks lain.

PENTING untuk customer_name:
Daftar customer yang tersedia di database: [$customerList]
Pilih nama customer yang PALING COCOK dari daftar di atas berdasarkan kemiripan huruf/suku kata.
Contoh: jika user menulis "askot" dan di daftar ada "Ascott", maka pilih "Ascott".
Jika tidak ada yang cocok sama sekali, gunakan nama dari teks user.

Format JSON:
{
  "customer_name": "nama instansi paling cocok dari daftar atau kosong",
  "perihal_surat": "judul surat atau kosong",
  "perihal": ["kelompok pekerjaan 1", "kelompok pekerjaan 2"],
  "items": [
    {
      "nama_item": "nama item (boleh kosong)",
      "deskripsi": "deskripsi",
      "volume": "1",
      "satuan": "Unit",
      "harga_satuan": 0,
      "diskon": 0
    }
  ],
  "catatan": ""
}

Contoh 1:
Input: "Penawaran pengadaan 50 set seragam sekolah untuk RSUD Sultan Thaha"
Output: {"customer_name":"RSUD Sultan Thaha","perihal_surat":"Pengadaan Seragam Sekolah","perihal":["Seragam Sekolah"],"items":[{"nama_item":"Seragam Sekolah","deskripsi":"Pengadaan 50 set seragam sekolah","volume":"50","satuan":"Set","harga_satuan":250000,"diskon":0}],"catatan":""}

Contoh 2:
Input: "Service X-Ray 1 unit @5.000.000 untuk RSUD Ahmad, diskon 5%, catatan Harga sudah termasuk transport"
Output: {"customer_name":"RSUD Ahmad","perihal_surat":"Service X-Ray","perihal":["Service X-Ray"],"items":[{"nama_item":"Service X-Ray","deskripsi":"Service X-Ray 1 unit","volume":"1","satuan":"Unit","harga_satuan":5000000,"diskon":5}],"catatan":"Harga sudah termasuk transport"}
PROMPT;

        $text = match ($provider) {
            'openrouter' => $this->callOpenRouter($systemPrompt, $request->prompt),
            default => $this->callGemini($systemPrompt, $request->prompt),
        };

        if ($text instanceof \Illuminate\Http\JsonResponse) return $text;

        $text = trim(preg_replace('/^```(?:json)?\s*|\s*```$/', '', $text));
        $data = json_decode($text, true);
        if (!$data || !isset($data['items'])) {
            return response()->json(['error' => 'AI tidak dapat memahami prompt. Coba lebih detail.'], 422);
        }

        // Cari customer (fuzzy search)
        $customer = null;
        $foundCustomers = collect();
        if (!empty($data['customer_name'])) {
            $searchName = $data['customer_name'];

            // 1. Exact match
            $foundCustomers = Customer::where('nama_instansi', $searchName)->get();

            // 2. Case-insensitive LIKE
            if ($foundCustomers->isEmpty()) {
                $foundCustomers = Customer::whereRaw('LOWER(nama_instansi) LIKE ?', ['%' . strtolower($searchName) . '%'])->get();
            }

            // 3. SOUNDEX (kemiripan bunyi: askot ≈ ascott)
            if ($foundCustomers->isEmpty()) {
                $foundCustomers = Customer::whereRaw('SOUNDEX(nama_instansi) = SOUNDEX(?)', [$searchName])->get();
            }

            // 4. Multi-kata: pecah input, cari yang paling banyak cocok
            if ($foundCustomers->isEmpty()) {
                $words = array_filter(explode(' ', strtolower($searchName)));
                if (count($words) > 0) {
                    $foundCustomers = Customer::where(function ($q) use ($words) {
                        foreach ($words as $word) {
                            $q->orWhereRaw('LOWER(nama_instansi) LIKE ?', ['%' . $word . '%']);
                        }
                    })->get();
                }
            }

            $customer = $foundCustomers->first();
        }

        // perihal fallback: eksplisit -> nama_item unik -> 'Penawaran'
        $perihal = [];
        if (!empty($data['perihal']) && is_array($data['perihal'])) {
            $perihal = array_values(array_filter(array_map('trim', $data['perihal']), fn($p) => $p !== ''));
        }
        if (empty($perihal)) {
            $perihal = array_values(array_unique(array_filter(array_map(
                fn($it) => trim($it['nama_item'] ?? ''),
                $data['items']
            ), fn($n) => $n !== '')));
        }
        if (empty($perihal)) {
            $perihal = ['Penawaran'];
        }

        // Hitung total & subtotal (dengan diskon)
        $total = 0;
        foreach ($data['items'] as &$item) {
            $harga = (float) ($item['harga_satuan'] ?? 0);
            $volume = (float) ($item['volume'] ?? 1);
            $diskon = (float) ($item['diskon'] ?? 0);
            $subtotal = $diskon > 0 ? $volume * $harga * (100 - $diskon) / 100 : $volume * $harga;
            $item['subtotal'] = $subtotal;
            $item['diskon'] = $diskon > 0 ? $diskon : null;
            $total += $subtotal;
        }

        $previewData = [
            'tanggal' => now()->format('Y-m-d'),
            'customer' => $customer,
            'customer_name' => $data['customer_name'] ?? '',
            'foundCustomers' => $foundCustomers,
            'perihal' => $perihal,
            'perihal_surat' => $data['perihal_surat'] ?? '',
            'items' => $data['items'],
            'total' => $total,
            'catatan' => $data['catatan'] ?? '',
        ];

        $html = view('admin.quotations._preview', $previewData)->render();

        return response()->json([
            'html' => $html,
            'data' => [
                'customer_id' => $customer?->id,
                'customer_name' => $data['customer_name'] ?? '',
                'perihal' => $perihal,
                'perihal_surat' => $data['perihal_surat'] ?? '',
                'items' => $data['items'],
                'total' => $total,
                'catatan' => $data['catatan'] ?? '',
            ],
        ]);
    }

    /**
     * Store quotation generated by AI (redirect ke halaman detail).
     */
    public function aiStore(Request $request)
    {
        try {
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'perihal' => 'nullable|array|min:1',
                'perihal.*' => 'required|string|max:255',
                'perihal_surat' => 'nullable|string|max:255',
                'items' => 'required|array|min:1',
                'items.*.nama_item' => 'nullable|string|max:255',
                'items.*.deskripsi' => 'required|string',
                'items.*.volume' => 'required|string|max:255',
                'items.*.satuan' => 'nullable|string|max:50',
                'items.*.harga_satuan' => 'required',
                'items.*.diskon' => 'nullable|numeric|min:0|max:100',
                'items.*.subtotal' => 'required',
                'catatan' => 'nullable|string',
                'kata_penutup' => 'nullable|string',
            ]);

            $total = 0;
            $itemsData = [];
            $perihalArray = $request->perihal ?? [];
            if (empty($perihalArray)) {
                $perihalArray = array_values(array_unique(array_filter(array_map(
                    fn($it) => trim($it['nama_item'] ?? ''),
                    $request->items
                ), fn($n) => $n !== '')));
            }
            if (empty($perihalArray)) {
                $perihalArray = ['Penawaran'];
            }

            $itemIndex = 0;
            foreach ($request->items as $item) {
                $harga = (float) str_replace(['.', ','], ['', '.'], $item['harga_satuan']);
                $volume = (float) str_replace(['.', ','], ['', '.'], $item['volume']);
                $diskon = !empty($item['diskon']) ? (float) $item['diskon'] : 0;
                $subtotal = (float) str_replace(['.', ','], ['', '.'], $item['subtotal']);
                if ($subtotal <= 0) {
                    $subtotal = $diskon > 0 ? $volume * $harga * (100 - $diskon) / 100 : $volume * $harga;
                }
                $total += $subtotal;

                $namaItem = ! empty($item['nama_item']) ? $item['nama_item'] : ($perihalArray[$itemIndex] ?? null);

                $itemsData[] = [
                    'nama_item' => $namaItem,
                    'deskripsi' => $item['deskripsi'],
                    'volume' => $item['volume'],
                    'satuan' => $item['satuan'] ?? null,
                    'harga_satuan' => $harga,
                    'diskon' => $diskon > 0 ? $diskon : null,
                    'subtotal' => $subtotal,
                    'tampilkan_label' => true,
                ];
                $itemIndex++;
            }

            $quotation = DB::transaction(function () use ($request, $itemsData, $total, $perihalArray) {
                $month = date('n');
                $year = date('Y');
                $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                $romanMonth = $romans[$month - 1];

                $lastQuotation = Quotation::whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $month)
                    ->orderBy('id', 'desc')
                    ->first();

                $nextNumber = $month * 100 + 1;
                if ($lastQuotation) {
                    $parts = explode('/', $lastQuotation->nomor_surat);
                    $lastNumber = intval($parts[0]);
                    $nextNumber = max($month * 100 + 1, $lastNumber + 1);
                }

                $nomorSurat = sprintf('%03d/SP/PIB-JMB/%s/%s', $nextNumber, $romanMonth, $year);

                $quotation = Quotation::create([
                    'nomor_surat' => $nomorSurat,
                    'tanggal' => now()->format('Y-m-d'),
                    'customer_id' => $request->customer_id,
                    'perihal' => json_encode(array_values($perihalArray)),
                    'perihal_surat' => $request->perihal_surat,
                    'catatan' => $request->filled('catatan') ? trim($request->catatan) : null,
                    'kata_penutup' => $request->kata_penutup,
                    'status' => 'draft',
                    'tampilkan_gambar' => false,
                    'selected_images' => [],
                    'total' => 0, // will be calculated
                ]);

                foreach ($itemsData as $item) {
                    $quotation->items()->create($item);
                }

                $quotation->update(['total' => $total]);

                return $quotation;
            });

            return response()->json([
                'success' => true,
                'redirect' => route('quotations.show', $quotation->id),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan penawaran: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store quotation generated by AI sebagai draft (redirect ke halaman edit).
     */
    public function aiStoreDraft(Request $request)
    {
        try {
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'perihal' => 'nullable|array|min:1',
                'perihal.*' => 'required|string|max:255',
                'perihal_surat' => 'nullable|string|max:255',
                'items' => 'required|array|min:1',
                'items.*.nama_item' => 'nullable|string|max:255',
                'items.*.deskripsi' => 'required|string',
                'items.*.volume' => 'required|string|max:255',
                'items.*.satuan' => 'nullable|string|max:50',
                'items.*.harga_satuan' => 'required',
                'items.*.diskon' => 'nullable|numeric|min:0|max:100',
                'items.*.subtotal' => 'required',
                'catatan' => 'nullable|string',
                'kata_penutup' => 'nullable|string',
            ]);

            $total = 0;
            $itemsData = [];
            $perihalArray = $request->perihal ?? [];
            if (empty($perihalArray)) {
                $perihalArray = array_values(array_unique(array_filter(array_map(
                    fn($it) => trim($it['nama_item'] ?? ''),
                    $request->items
                ), fn($n) => $n !== '')));
            }
            if (empty($perihalArray)) {
                $perihalArray = ['Penawaran'];
            }

            $itemIndex = 0;
            foreach ($request->items as $item) {
                $harga = (float) str_replace(['.', ','], ['', '.'], $item['harga_satuan']);
                $volume = (float) str_replace(['.', ','], ['', '.'], $item['volume']);
                $diskon = !empty($item['diskon']) ? (float) $item['diskon'] : 0;
                $subtotal = (float) str_replace(['.', ','], ['', '.'], $item['subtotal']);
                if ($subtotal <= 0) {
                    $subtotal = $diskon > 0 ? $volume * $harga * (100 - $diskon) / 100 : $volume * $harga;
                }
                $total += $subtotal;

                $namaItem = ! empty($item['nama_item']) ? $item['nama_item'] : ($perihalArray[$itemIndex] ?? null);

                $itemsData[] = [
                    'nama_item' => $namaItem,
                    'deskripsi' => $item['deskripsi'],
                    'volume' => $item['volume'],
                    'satuan' => $item['satuan'] ?? null,
                    'harga_satuan' => $harga,
                    'diskon' => $diskon > 0 ? $diskon : null,
                    'subtotal' => $subtotal,
                    'tampilkan_label' => true,
                ];
                $itemIndex++;
            }

            $quotation = DB::transaction(function () use ($request, $itemsData, $total, $perihalArray) {
                $month = date('n');
                $year = date('Y');
                $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                $romanMonth = $romans[$month - 1];

                $lastQuotation = Quotation::whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $month)
                    ->orderBy('id', 'desc')
                    ->first();

                $nextNumber = $month * 100 + 1;
                if ($lastQuotation) {
                    $parts = explode('/', $lastQuotation->nomor_surat);
                    $lastNumber = intval($parts[0]);
                    $nextNumber = max($month * 100 + 1, $lastNumber + 1);
                }

                $nomorSurat = sprintf('%03d/SP/PIB-JMB/%s/%s', $nextNumber, $romanMonth, $year);

                $quotation = Quotation::create([
                    'nomor_surat' => $nomorSurat,
                    'tanggal' => now()->format('Y-m-d'),
                    'customer_id' => $request->customer_id,
                    'perihal' => json_encode(array_values($perihalArray)),
                    'perihal_surat' => $request->perihal_surat,
                    'catatan' => $request->filled('catatan') ? trim($request->catatan) : null,
                    'kata_penutup' => $request->kata_penutup,
                    'status' => 'draft',
                    'tampilkan_gambar' => false,
                    'selected_images' => [],
                    'total' => 0, // will be calculated
                ]);

                foreach ($itemsData as $item) {
                    $quotation->items()->create($item);
                }

                $quotation->update(['total' => $total]);

                return $quotation;
            });

            return response()->json([
                'success' => true,
                'redirect' => route('quotations.edit', $quotation->id),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan draft: ' . $e->getMessage()], 500);
        }
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

    private function callGemini($systemPrompt, $userPrompt)
    {
        $apiKey = config('services.gemini.api_key');
        if (!$apiKey) {
            return response()->json(['error' => 'Fitur AI (Gemini) belum aktif. Hubungi admin untuk mengatur GEMINI_API_KEY.'], 500);
        }

        try {
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        ['parts' => [['text' => $systemPrompt . "\nInput: " . $userPrompt . "\nOutput:"]],
                    ]]
                ]
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal terhubung ke server AI Gemini. Periksa koneksi internet.'], 500);
        }

        if (!$response->successful()) {
            $status = $response->status();
            $msg = match ($status) {
                429 => 'AI Gemini sedang sibuk. Tunggu 1-2 menit, lalu coba lagi.',
                503 => 'Server AI Gemini sedang sibuk. Coba lagi nanti.',
                400 => 'Prompt tidak dikenali. Coba tulis ulang dengan lebih jelas.',
                403 => 'Akses AI Gemini ditolak. Periksa GEMINI_API_KEY atau hubungi admin.',
                default => 'Gagal memproses AI Gemini (kode ' . $status . '). Coba lagi.',
            };
            return response()->json(['error' => $msg], 500);
        }

        $body = $response->json();
        return $body['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
    }

    private function callOpenRouter($systemPrompt, $userPrompt)
    {
        $apiKey = config('services.openrouter.api_key');
        if (!$apiKey) {
            return response()->json(['error' => 'Fitur AI (OpenRouter) belum aktif. Atur OPENROUTER_API_KEY di .env.'], 500);
        }

        $model = config('services.openrouter.model', 'google/gemini-2.5-flash-lite');

        try {
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'response_format' => ['type' => 'json_object'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal terhubung ke server AI OpenRouter. Periksa koneksi internet.'], 500);
        }

        if (!$response->successful()) {
            $status = $response->status();
            $body = $response->json();
            $errMsg = $body['error']['message'] ?? '';
            $msg = match (true) {
                $status === 401 || $status === 403 => 'Akses AI OpenRouter ditolak. Periksa OPENROUTER_API_KEY.',
                $status === 429 => 'AI OpenRouter sedang sibuk. Tunggu 1-2 menit, lalu coba lagi.',
                $status === 402 => 'Saldo OpenRouter habis. Isi ulang akun OpenRouter Anda.',
                !empty($errMsg) => 'OpenRouter error: ' . $errMsg,
                default => 'Gagal memproses AI OpenRouter (kode ' . $status . '). Coba lagi.',
            };
            return response()->json(['error' => $msg], 500);
        }

        $body = $response->json();
        return $body['choices'][0]['message']['content'] ?? '{}';
    }

    private function convertIndonesianDate($value)
    {
        if (empty($value)) return null;

        $value = trim($value);

        // Already Y-m-d
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) return $value;

        // Already Y-m-d from Carbon
        if (preg_match('/^\d{4}-\d{2}-\d{2} /', $value)) return substr($value, 0, 10);

        $months = [
            'januari' => '01', 'februari' => '02', 'maret' => '03', 'april' => '04',
            'mei' => '05', 'juni' => '06', 'juli' => '07', 'agustus' => '08',
            'september' => '09', 'oktober' => '10', 'november' => '11', 'desember' => '12',
        ];

        // "1 Juni 2026" or "Juni 2026"
        if (preg_match('/^(\d+)\s+(\S+)\s+(\d{4})$/', $value, $m)) {
            $day = str_pad($m[1], 2, '0', STR_PAD_LEFT);
            $month = $months[strtolower($m[2])] ?? null;
            return $month ? "{$m[3]}-{$month}-{$day}" : null;
        }
        if (preg_match('/^(\S+)\s+(\d{4})$/', $value, $m)) {
            $month = $months[strtolower($m[1])] ?? null;
            return $month ? "{$m[2]}-{$month}-01" : null;
        }

        return null;
    }
}

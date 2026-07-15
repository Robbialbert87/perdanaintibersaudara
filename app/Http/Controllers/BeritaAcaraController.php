<?php

namespace App\Http\Controllers;

use App\Helpers\QRCodeHelper;
use App\Models\BeritaAcara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BeritaAcaraController extends Controller
{
    public function index(Request $request)
    {
        $query = BeritaAcara::query();

        if ($request->has('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('kegiatan', 'like', "%{$search}%")
                  ->orWhere('pihak_penerima_nama', 'like', "%{$search}%");
            });
        }

        $beritaAcaras = $query->latest()->paginate(10);

        return view('admin.berita-acaras.index', compact('beritaAcaras'));
    }

    public function create()
    {
        return view('admin.berita-acaras.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
            'lokasi' => 'required|string',
            'pihak_penyerah_nama' => 'nullable|string|max:255',
            'pihak_penyerah_alamat' => 'nullable|string',
            'pihak_penerima_nama' => 'required|string|max:255',
            'pihak_penerima_alamat' => 'nullable|string',
            'closing_text' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.nama_produk' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.berfungsi' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request) {
            $month = date('n', strtotime($request->tanggal));
            $year = date('Y', strtotime($request->tanggal));
            $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $romanMonth = $romans[$month - 1];

            $lastBA = BeritaAcara::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = 101;
            if ($lastBA && $lastBA->nomor_surat) {
                $parts = explode('/', $lastBA->nomor_surat);
                $lastNumber = intval($parts[0]);
                $nextNumber = max(101, $lastNumber + 1);
            }

            $nomorSurat = sprintf('%03d/BA/PIB-JMB/%s/%s', $nextNumber, $romanMonth, $year);

            $beritaAcara = BeritaAcara::create([
                'nomor_surat' => $nomorSurat,
                'tanggal' => $request->tanggal,
                'kegiatan' => $request->kegiatan,
                'lokasi' => $request->lokasi,
                'pihak_penyerah_nama' => $request->pihak_penyerah_nama ?: 'CV. Perdana Inti Bersaudara',
                'pihak_penyerah_alamat' => $request->pihak_penyerah_alamat,
                'pihak_penerima_nama' => $request->pihak_penerima_nama,
                'pihak_penerima_alamat' => $request->pihak_penerima_alamat,
                'closing_text' => $request->filled('closing_text') ? trim($request->closing_text) : null,
                'status' => 'draft',
            ]);

            foreach ($request->items as $item) {
                $beritaAcara->items()->create([
                    'nama_produk' => $item['nama_produk'],
                    'quantity' => $item['quantity'],
                    'berfungsi' => $item['berfungsi'] === '1' || $item['berfungsi'] === true,
                ]);
            }
        });

        return redirect()->route('berita-acaras.index')->with('success', 'Berita Acara berhasil dibuat.');
    }

    public function show(string $id)
    {
        $beritaAcara = BeritaAcara::with('items')->findOrFail($id);

        return view('admin.berita-acaras.show', compact('beritaAcara'));
    }

    public function edit(string $id)
    {
        $beritaAcara = BeritaAcara::with('items')->findOrFail($id);

        return view('admin.berita-acaras.edit', compact('beritaAcara'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
            'lokasi' => 'required|string',
            'pihak_penyerah_nama' => 'nullable|string|max:255',
            'pihak_penyerah_alamat' => 'nullable|string',
            'pihak_penerima_nama' => 'required|string|max:255',
            'pihak_penerima_alamat' => 'nullable|string',
            'closing_text' => 'nullable|string',
            'status' => 'required|in:draft,dikirim,selesai,batal',
            'items' => 'required|array|min:1',
            'items.*.nama_produk' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.berfungsi' => 'required|boolean',
        ]);

        $beritaAcara = BeritaAcara::findOrFail($id);

        DB::transaction(function () use ($request, $beritaAcara) {
            $beritaAcara->update([
                'tanggal' => $request->tanggal,
                'kegiatan' => $request->kegiatan,
                'lokasi' => $request->lokasi,
                'pihak_penyerah_nama' => $request->pihak_penyerah_nama ?: 'CV. Perdana Inti Bersaudara',
                'pihak_penyerah_alamat' => $request->pihak_penyerah_alamat,
                'pihak_penerima_nama' => $request->pihak_penerima_nama,
                'pihak_penerima_alamat' => $request->pihak_penerima_alamat,
                'closing_text' => $request->filled('closing_text') ? trim($request->closing_text) : null,
                'status' => $request->status,
            ]);

            $beritaAcara->items()->delete();

            foreach ($request->items as $item) {
                $beritaAcara->items()->create([
                    'nama_produk' => $item['nama_produk'],
                    'quantity' => $item['quantity'],
                    'berfungsi' => $item['berfungsi'] === '1' || $item['berfungsi'] === true,
                ]);
            }
        });

        return redirect()->route('berita-acaras.index')->with('success', 'Berita Acara berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $beritaAcara = BeritaAcara::findOrFail($id);
        $beritaAcara->delete();

        return redirect()->route('berita-acaras.index')->with('success', 'Berita Acara berhasil dihapus.');
    }

    public function exportPdf(string $id)
    {
        $beritaAcara = BeritaAcara::with('items')->findOrFail($id);

        if (empty($beritaAcara->verify_token)) {
            $beritaAcara->update(['verify_token' => (string) Str::uuid()]);
            $beritaAcara->refresh();
        }

        $verifyUrl = route('verify.berita_acara', $beritaAcara->verify_token);
        $qrCode = QRCodeHelper::generate($verifyUrl, 150);

        $pdf = app('dompdf.wrapper')->loadView('admin.berita-acaras.pdf', compact('beritaAcara', 'qrCode'))
            ->setPaper([0, 0, 595.28, 935.43], 'portrait');

        $filename = 'Berita_Acara_'.str_replace('/', '_', $beritaAcara->nomor_surat).'.pdf';

        return $pdf->download($filename);
    }

    public function previewPdf(string $id)
    {
        $beritaAcara = BeritaAcara::with('items')->findOrFail($id);

        if (empty($beritaAcara->verify_token)) {
            $beritaAcara->update(['verify_token' => (string) Str::uuid()]);
            $beritaAcara->refresh();
        }

        $verifyUrl = route('verify.berita_acara', $beritaAcara->verify_token);
        $qrCode = QRCodeHelper::generate($verifyUrl, 150);

        $pdf = app('dompdf.wrapper')->loadView('admin.berita-acaras.pdf', compact('beritaAcara', 'qrCode'))
            ->setPaper([0, 0, 595.28, 935.43], 'portrait');

        return $pdf->inline();
    }

    public function print(string $id)
    {
        $beritaAcara = BeritaAcara::with('items')->findOrFail($id);

        if (empty($beritaAcara->verify_token)) {
            $beritaAcara->update(['verify_token' => (string) Str::uuid()]);
            $beritaAcara->refresh();
        }

        $verifyUrl = route('verify.berita_acara', $beritaAcara->verify_token);
        $qrCode = QRCodeHelper::generate($verifyUrl, 150);

        return view('admin.berita-acaras.pdf', compact('beritaAcara', 'qrCode'));
    }
}

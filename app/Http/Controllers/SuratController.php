<?php

namespace App\Http\Controllers;

use App\Helpers\QRCodeHelper;
use App\Models\Customer;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuratController extends Controller
{
    private const JENIS = ['Permintaan', 'Pengantar', 'Keterangan', 'Pemberitahuan', 'Permohonan', 'Lainnya'];

    private const KODE_JENIS = [
        'Permintaan' => 'SPM',
        'Pengantar' => 'SPG',
        'Keterangan' => 'SK',
        'Pemberitahuan' => 'SPB',
        'Permohonan' => 'SPMOH',
        'Lainnya' => 'SURAT',
    ];

    public function index(Request $request)
    {
        $query = Surat::with('customer');

        if ($request->has('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('jenis', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('tujuan_nama', 'like', "%{$search}%");
            });
        }

        $surats = $query->latest()->paginate(10);

        return view('admin.surats.index', compact('surats'));
    }

    public function create()
    {
        $customers = Customer::orderBy('nama_instansi')->get();
        $jenisOptions = self::JENIS;
        $nomorSurat = $this->generateNomorSurat('Permintaan', date('Y-m-d'));

        return view('admin.surats.create', compact('customers', 'jenisOptions', 'nomorSurat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'nullable|string|max:100|unique:surats,nomor_surat',
            'jenis' => 'required|in:'.implode(',', self::JENIS),
            'tanggal' => 'required|date',
            'customer_id' => 'nullable|exists:customers,id',
            'tujuan_nama' => 'required_without:customer_id|nullable|string|max:255',
            'tujuan_alamat' => 'nullable|string',
            'perihal' => 'required|string|max:255',
            'isi' => 'required|string',
            'lampiran' => 'nullable|array|max:10',
            'lampiran.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'nama_penandatangan' => 'required|string|max:255',
            'jabatan_penandatangan' => 'nullable|string|max:100',
            'status' => 'required|in:draft,dikirim,selesai,batal',
        ]);

        $customer = $validated['customer_id'] ? Customer::findOrFail($validated['customer_id']) : null;

        $lampiranPaths = [];
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $lampiranPaths[] = $file->store('surats/lampiran', 'public');
            }
            $optimizer = app(\App\Support\ImageOptimizer::class);
            foreach ($lampiranPaths as $path) {
                $optimizer->optimizeStoragePath($path);
            }
        }

        if (empty($validated['nomor_surat'])) {
            $validated['nomor_surat'] = $this->generateNomorSurat($validated['jenis'], $validated['tanggal']);
        }

        $surat = Surat::create([
            'nomor_surat' => $validated['nomor_surat'],
            'jenis' => $validated['jenis'],
            'tanggal' => $validated['tanggal'],
            'customer_id' => $customer?->id,
            'tujuan_nama' => ($validated['tujuan_nama'] ?? null) ?: ($customer?->nama_instansi ?? ''),
            'tujuan_alamat' => ($validated['tujuan_alamat'] ?? null) ?: ($customer->alamat ?? null),
            'perihal' => $validated['perihal'],
            'isi' => $validated['isi'],
            'lampiran' => $lampiranPaths ?: null,
            'nama_penandatangan' => $validated['nama_penandatangan'],
            'jabatan_penandatangan' => $validated['jabatan_penandatangan'] ?: 'Direktur',
            'status' => $validated['status'],
            'verify_token' => (string) Str::uuid(),
        ]);

        return redirect()->route('surats.index')->with('success', 'Surat berhasil dibuat.');
    }

    public function show(string $id)
    {
        $surat = Surat::with('customer')->findOrFail($id);

        return view('admin.surats.show', compact('surat'));
    }

    public function edit(string $id)
    {
        $surat = Surat::findOrFail($id);
        $customers = Customer::orderBy('nama_instansi')->get();
        $jenisOptions = self::JENIS;

        return view('admin.surats.edit', compact('surat', 'customers', 'jenisOptions'));
    }

    public function ajaxNomor(Request $request)
    {
        $data = $request->validate([
            'jenis' => 'required|in:'.implode(',', self::JENIS),
            'tanggal' => 'required|date',
            'except' => 'nullable|integer|exists:surats,id',
        ]);

        return response()->json([
            'nomor' => $this->generateNomorSurat($data['jenis'], $data['tanggal'], $data['except'] ?? null),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $surat = Surat::findOrFail($id);

        $jenisBaru = (string) $request->input('jenis', '');
        $tanggalBaru = (string) $request->input('tanggal', '');
        if (
            in_array($jenisBaru, self::JENIS, true)
            && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalBaru)
            && ($jenisBaru !== $surat->jenis || $tanggalBaru !== $surat->tanggal->format('Y-m-d'))
        ) {
            $request->merge([
                'nomor_surat' => $this->generateNomorSurat($jenisBaru, $tanggalBaru, $surat->id),
            ]);
        }

        $validated = $request->validate([
            'nomor_surat' => 'nullable|string|max:100|unique:surats,nomor_surat,'.$surat->id,
            'jenis' => 'required|in:'.implode(',', self::JENIS),
            'tanggal' => 'required|date',
            'customer_id' => 'nullable|exists:customers,id',
            'tujuan_nama' => 'required_without:customer_id|nullable|string|max:255',
            'tujuan_alamat' => 'nullable|string',
            'perihal' => 'required|string|max:255',
            'isi' => 'required|string',
            'lampiran' => 'nullable|array|max:10',
            'lampiran.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'remove_lampiran' => 'nullable|array',
            'nama_penandatangan' => 'required|string|max:255',
            'jabatan_penandatangan' => 'nullable|string|max:100',
            'status' => 'required|in:draft,dikirim,selesai,batal',
        ]);

        $customer = $validated['customer_id'] ? Customer::findOrFail($validated['customer_id']) : null;

        $lampiran = $surat->lampiran ?? [];
        foreach ($request->input('remove_lampiran', []) as $path) {
            if (in_array($path, $lampiran, true)) {
                Storage::disk('public')->delete($path);
                $lampiran = array_values(array_filter($lampiran, fn($p) => $p !== $path));
            }
        }
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $lampiran[] = $file->store('surats/lampiran', 'public');
            }
            $optimizer = app(\App\Support\ImageOptimizer::class);
            foreach ($lampiran as $path) {
                $optimizer->optimizeStoragePath($path);
            }
        }
        if (count($lampiran) > 10) {
            $lampiran = array_slice($lampiran, 0, 10);
        }

        if (empty($validated['nomor_surat'])) {
            $validated['nomor_surat'] = $this->generateNomorSurat($validated['jenis'], $validated['tanggal'], $surat->id);
        }

        $surat->update([
            'nomor_surat' => $validated['nomor_surat'],
            'jenis' => $validated['jenis'],
            'tanggal' => $validated['tanggal'],
            'customer_id' => $customer?->id,
            'tujuan_nama' => ($validated['tujuan_nama'] ?? null) ?: ($customer->nama_instansi ?? $surat->tujuan_nama),
            'tujuan_alamat' => ($validated['tujuan_alamat'] ?? null) ?: ($customer->alamat ?? null),
            'perihal' => $validated['perihal'],
            'isi' => $validated['isi'],
            'lampiran' => $lampiran ?: null,
            'nama_penandatangan' => $validated['nama_penandatangan'],
            'jabatan_penandatangan' => $validated['jabatan_penandatangan'] ?: 'Direktur',
            'status' => $validated['status'],
        ]);

        return redirect()->route('surats.index')->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $surat = Surat::findOrFail($id);
        foreach ($surat->lampiran ?? [] as $path) {
            Storage::disk('public')->delete($path);
        }
        $surat->delete();

        return redirect()->route('surats.index')->with('success', 'Surat berhasil dihapus.');
    }

    public function exportPdf(string $id)
    {
        $surat = Surat::findOrFail($id);
        $qrCode = $this->qrCode($surat);

        $pdf = app('dompdf.wrapper')->loadView('admin.surats.pdf', compact('surat', 'qrCode'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Surat_'.str_replace('/', '_', $surat->nomor_surat).'.pdf');
    }

    public function previewPdf(string $id)
    {
        $surat = Surat::findOrFail($id);
        $qrCode = $this->qrCode($surat);

        $pdf = app('dompdf.wrapper')->loadView('admin.surats.pdf', compact('surat', 'qrCode'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Surat_'.str_replace('/', '_', $surat->nomor_surat).'.pdf');
    }

    public function print(string $id)
    {
        $surat = Surat::findOrFail($id);
        $qrCode = $this->qrCode($surat);

        return view('admin.surats.pdf', compact('surat', 'qrCode'));
    }

    private function qrCode(Surat $surat): string
    {
        if (empty($surat->verify_token)) {
            $surat->update(['verify_token' => (string) Str::uuid()]);
            $surat->refresh();
        }

        return QRCodeHelper::generate(route('verify.surat', $surat->verify_token), 150);
    }

    private function generateNomorSurat(string $jenis, string $tanggal, ?int $exceptId = null): string
    {
        $month = (int) date('n', strtotime($tanggal));
        $year = date('Y', strtotime($tanggal));
        $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

        $last = Surat::where('jenis', $jenis)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->when($exceptId, fn($q) => $q->where('id', '!=', $exceptId))
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $month * 100 + 1;
        if ($last && $last->nomor_surat) {
            $parts = explode('/', $last->nomor_surat);
            $nextNumber = max($nextNumber, (int) $parts[0] + 1);
        }

        $kode = self::KODE_JENIS[$jenis] ?? 'SURAT';

        while (true) {
            $nomor = sprintf('%03d/%s/PIB-JMB/%s/%s', $nextNumber, $kode, $romans[$month - 1], $year);
            $terpakai = Surat::where('nomor_surat', $nomor)
                ->when($exceptId, fn($q) => $q->where('id', '!=', $exceptId))
                ->exists();

            if (! $terpakai) {
                return $nomor;
            }

            $nextNumber++;
        }
    }
}

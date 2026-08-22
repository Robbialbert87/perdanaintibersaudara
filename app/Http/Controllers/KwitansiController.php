<?php

namespace App\Http\Controllers;

use App\Helpers\QRCodeHelper;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Kwitansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KwitansiController extends Controller
{
    public function index(Request $request)
    {
        $query = Kwitansi::with('customer', 'invoice');

        if ($request->has('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->get('search'));
            $query->where('nomor_kwitansi', 'like', "%{$search}%")
                ->orWhere('untuk_pembayaran', 'like', "%{$search}%")
                ->orWhereHas('customer', function ($q) use ($search) {
                    $q->where('nama_instansi', 'like', "%{$search}%");
                });
        }

        $kwitansis = $query->latest()->paginate(10);

        return view('admin.kwitansis.index', compact('kwitansis'));
    }

    public function create()
    {
        $customers = Customer::orderBy('nama_instansi')->get();
        $invoices = Invoice::with('customer')->orderByDesc('tanggal')->get();

        return view('admin.kwitansis.create', compact('customers', 'invoices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'jumlah' => 'required',
            'untuk_pembayaran' => 'nullable|string|max:500',
            'catatan' => 'nullable|string|max:2000',
        ]);

        $jumlah = (float) str_replace(['.', ','], ['', '.'], $request->jumlah);

        DB::transaction(function () use ($request, $jumlah) {
            $month = date('n', strtotime($request->tanggal));
            $year = date('Y', strtotime($request->tanggal));
            $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $romanMonth = $romans[$month - 1];

            $lastKwitansi = Kwitansi::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = $month * 100 + 1;
            if ($lastKwitansi) {
                $parts = explode('/', $lastKwitansi->nomor_kwitansi);
                $nextNumber = max($month * 100 + 1, intval($parts[0]) + 1);
            }

            $nomorKwitansi = sprintf('%03d/KW/PIB-JMB/%s/%s', $nextNumber, $romanMonth, $year);

            Kwitansi::create([
                'nomor_kwitansi' => $nomorKwitansi,
                'tanggal' => $request->tanggal,
                'customer_id' => $request->customer_id,
                'invoice_id' => $request->invoice_id,
                'jumlah' => $jumlah,
                'untuk_pembayaran' => $request->filled('untuk_pembayaran') ? trim($request->untuk_pembayaran) : null,
                'catatan' => $request->filled('catatan') ? trim($request->catatan) : null,
            ]);
        });

        return redirect()->route('kwitansis.index')->with('success', 'Kwitansi berhasil dibuat.');
    }

    public function show(string $id)
    {
        $kwitansi = Kwitansi::with('customer', 'invoice')->findOrFail($id);

        return view('admin.kwitansis.show', compact('kwitansi'));
    }

    public function exportPdf(string $id)
    {
        $kwitansi = Kwitansi::with('customer', 'invoice')->findOrFail($id);

        if (empty($kwitansi->verify_token)) {
            $kwitansi->update(['verify_token' => (string) Str::uuid()]);
            $kwitansi->refresh();
        }

        $verifyUrl = route('verify.kwitansi', $kwitansi->verify_token);
        $qrCode = QRCodeHelper::generate($verifyUrl, 150);

        // F4 portrait, sama seperti penawaran/invoice
        $pdf = app('dompdf.wrapper')->loadView('admin.kwitansis.pdf', compact('kwitansi', 'qrCode'))
            ->setPaper([0, 0, 595.28, 935.43], 'portrait');

        $filename = 'Kwitansi_'.str_replace('/', '_', $kwitansi->nomor_kwitansi).'.pdf';

        return $pdf->download($filename);
    }

    public function edit(string $id)
    {
        $kwitansi = Kwitansi::with('customer', 'invoice')->findOrFail($id);
        $customers = Customer::orderBy('nama_instansi')->get();
        $invoices = Invoice::with('customer')->orderByDesc('tanggal')->get();

        return view('admin.kwitansis.edit', compact('kwitansi', 'customers', 'invoices'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'jumlah' => 'required',
            'untuk_pembayaran' => 'nullable|string|max:500',
            'catatan' => 'nullable|string|max:2000',
        ]);

        $kwitansi = Kwitansi::findOrFail($id);
        $jumlah = (float) str_replace(['.', ','], ['', '.'], $request->jumlah);

        $kwitansi->update([
            'tanggal' => $request->tanggal,
            'customer_id' => $request->customer_id,
            'invoice_id' => $request->invoice_id,
            'jumlah' => $jumlah,
            'untuk_pembayaran' => $request->filled('untuk_pembayaran') ? trim($request->untuk_pembayaran) : null,
            'catatan' => $request->filled('catatan') ? trim($request->catatan) : null,
        ]);

        return redirect()->route('kwitansis.index')->with('success', 'Kwitansi berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $kwitansi = Kwitansi::findOrFail($id);
        $kwitansi->delete();

        return redirect()->route('kwitansis.index')->with('success', 'Kwitansi berhasil dihapus.');
    }
}

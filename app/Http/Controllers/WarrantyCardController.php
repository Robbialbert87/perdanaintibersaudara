<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\WarrantyCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WarrantyCardController extends Controller
{
    public function index(Request $request)
    {
        $query = WarrantyCard::with('customer');

        if ($request->has('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->get('search'));
            $query->where('nomor_kartu', 'like', "%{$search}%")
                ->orWhere('nama_alat', 'like', "%{$search}%")
                ->orWhere('nama_rs_klinik', 'like', "%{$search}%");
        }

        $warrantyCards = $query->latest()->paginate(10);

        return view('admin.warranty-cards.index', compact('warrantyCards'));
    }

    public function create()
    {
        $customers = Customer::orderBy('nama_instansi')->get();

        return view('admin.warranty-cards.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'customer_id' => 'nullable|exists:customers,id',
            'nama_alat' => 'required|string|max:255',
            'type_alat' => 'required|string|max:255',
            'nama_rs_klinik' => 'required|string|max:255',
            'tgl_instalasi' => 'required|date',
            'catatan' => 'nullable|string',
            'verifikator' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($request) {
            $month = date('n', strtotime($request->tanggal));
            $year = date('Y', strtotime($request->tanggal));
            $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $romanMonth = $romans[$month - 1];

            $lastCard = WarrantyCard::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = 101;
            if ($lastCard) {
                $parts = explode('/', $lastCard->nomor_kartu);
                $lastNumber = intval($parts[0]);
                $nextNumber = max(101, $lastNumber + 1);
            }

            $nomorKartu = sprintf('%03d/GAR/PIB-JMB/%s/%s', $nextNumber, $romanMonth, $year);

            WarrantyCard::create([
                'nomor_kartu' => $nomorKartu,
                'tanggal' => $request->tanggal,
                'customer_id' => $request->customer_id,
                'nama_alat' => $request->nama_alat,
                'type_alat' => $request->type_alat,
                'nama_rs_klinik' => $request->nama_rs_klinik,
                'tgl_instalasi' => $request->tgl_instalasi,
                'catatan' => $request->filled('catatan') ? trim($request->catatan) : null,
                'verifikator' => $request->verifikator,
                'ttd_pembeli' => false,
            ]);
        });

        return redirect()->route('warranty-cards.index')->with('success', 'Kartu Garansi berhasil dibuat.');
    }

    public function show(string $id)
    {
        $warrantyCard = WarrantyCard::with('customer')->findOrFail($id);

        return view('admin.warranty-cards.show', compact('warrantyCard'));
    }

    public function exportPdf(string $id)
    {
        $warrantyCard = WarrantyCard::with('customer')->findOrFail($id);

        $pdf = app('dompdf.wrapper')->loadView('admin.warranty-cards.pdf', compact('warrantyCard'))
            ->setPaper([0, 0, 595.28, 841.89], 'portrait');

        $filename = 'Kartu_Garansi_'.str_replace('/', '_', $warrantyCard->nomor_kartu).'.pdf';

        return $pdf->download($filename);
    }

    public function edit(string $id)
    {
        $warrantyCard = WarrantyCard::findOrFail($id);
        $customers = Customer::orderBy('nama_instansi')->get();

        return view('admin.warranty-cards.edit', compact('warrantyCard', 'customers'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'customer_id' => 'nullable|exists:customers,id',
            'nama_alat' => 'required|string|max:255',
            'type_alat' => 'required|string|max:255',
            'nama_rs_klinik' => 'required|string|max:255',
            'tgl_instalasi' => 'required|date',
            'catatan' => 'nullable|string',
            'verifikator' => 'nullable|string|max:100',
            'ttd_pembeli' => 'nullable|boolean',
        ]);

        $warrantyCard = WarrantyCard::findOrFail($id);

        $warrantyCard->update([
            'tanggal' => $request->tanggal,
            'customer_id' => $request->customer_id,
            'nama_alat' => $request->nama_alat,
            'type_alat' => $request->type_alat,
            'nama_rs_klinik' => $request->nama_rs_klinik,
            'tgl_instalasi' => $request->tgl_instalasi,
            'catatan' => $request->filled('catatan') ? trim($request->catatan) : null,
            'verifikator' => $request->verifikator,
            'ttd_pembeli' => $request->boolean('ttd_pembeli'),
        ]);

        return redirect()->route('warranty-cards.index')->with('success', 'Kartu Garansi berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $warrantyCard = WarrantyCard::findOrFail($id);
        $warrantyCard->delete();

        return redirect()->route('warranty-cards.index')->with('success', 'Kartu Garansi berhasil dihapus.');
    }
}

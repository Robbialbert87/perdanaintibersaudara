<?php

namespace App\Http\Controllers;

use App\Helpers\QRCodeHelper;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $services = Service::orderBy('title')->get();

        return view('admin.invoices.create', compact('customers', 'services'));
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
                'catatan' => $request->catatan,
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
        $services = Service::orderBy('title')->get();

        return view('admin.invoices.edit', compact('invoice', 'customers', 'services'));
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
                'catatan' => $request->catatan,
            ]);

            $invoice->items()->delete();

            foreach ($itemsData as $item) {
                $invoice->items()->create($item);
            }

            $invoice->update(['total' => $total]);
        });

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil diperbarui.');
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

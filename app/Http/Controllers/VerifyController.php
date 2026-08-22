<?php

namespace App\Http\Controllers;

use App\Models\BeritaAcara;
use App\Models\Invoice;
use App\Models\Kwitansi;
use App\Models\PurchaseOrder;
use App\Models\Quotation;
use App\Models\WarrantyCard;
use Carbon\Carbon;

class VerifyController extends Controller
{
    public function show(string $token)
    {
        $invoice = Invoice::with('customer')->where('verify_token', $token)->first();

        if (! $invoice) {
            return view('style.verify-not-found');
        }

        $tanggalGenerate = Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i').' WIB';

        return view('style.verify', compact('invoice', 'tanggalGenerate'));
    }

    public function showQuotation(string $token)
    {
        $quotation = Quotation::with(['customer', 'items.product'])->where('verify_token', $token)->first();

        if (! $quotation) {
            return view('style.verify-not-found');
        }

        $tanggalGenerate = Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i').' WIB';

        return view('style.verify-quotation', compact('quotation', 'tanggalGenerate'));
    }

    public function showPurchaseOrder(string $token)
    {
        $purchaseOrder = PurchaseOrder::with(['customer', 'items.product'])->where('verify_token', $token)->first();

        if (! $purchaseOrder) {
            return view('style.verify-not-found');
        }

        $tanggalGenerate = Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i').' WIB';

        return view('style.verify-purchase-order', compact('purchaseOrder', 'tanggalGenerate'));
    }

    public function showWarrantyCard(string $token)
    {
        $warrantyCard = WarrantyCard::with('customer')->where('verify_token', $token)->first();

        if (! $warrantyCard) {
            return view('style.verify-not-found');
        }

        $tanggalGenerate = Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i').' WIB';

        return view('style.verify-warranty-card', compact('warrantyCard', 'tanggalGenerate'));
    }

    public function showBeritaAcara(string $token)
    {
        $beritaAcara = BeritaAcara::with('items')->where('verify_token', $token)->first();

        if (! $beritaAcara) {
            return view('style.verify-not-found');
        }

        $tanggalGenerate = Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i').' WIB';

        return view('style.verify-berita-acara', compact('beritaAcara', 'tanggalGenerate'));
    }

    public function showKwitansi(string $token)
    {
        $kwitansi = Kwitansi::with('customer')->where('verify_token', $token)->first();

        if (! $kwitansi) {
            return view('style.verify-not-found');
        }

        $tanggalGenerate = Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i').' WIB';

        return view('style.verify-kwitansi', compact('kwitansi', 'tanggalGenerate'));
    }
}

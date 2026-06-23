@extends('layouts.admin')

@section('title', 'Daftar Invoice')

@push('styles')
<style>
.blink-draft {
    animation: blinkRed 1s ease-in-out infinite;
    background-color: #dc3545 !important;
    color: #fff !important;
    cursor: pointer;
}
@keyframes blinkRed {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}
</style>
@endpush

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 text-primary"><i class="bi bi-receipt me-2"></i>Daftar Invoice</h5>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#aiModal">
                <i class="bi bi-magic me-1"></i> Bantu AI
            </button>
            <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Buat Invoice
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('invoices.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nomor invoice, perihal, atau nama customer..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="">No</th>
                        <th>Nomor Invoice</th>
                        <th class="">Tanggal</th>
                        <th>Customer</th>
                        <th class="">Perihal</th>
                        <th class="">Total</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $index => $invoice)
                    <tr>
                        <td class="">{{ $invoices->firstItem() + $index }}</td>
                        <td class="text-nowrap">{{ $invoice->nomor_invoice }}</td>
                        <td class="">{{ date('d/m/Y', strtotime($invoice->tanggal)) }}</td>
                        <td class="text-nowrap">{{ $invoice->customer->nama_instansi }}</td>
                        <td class="">
                            @php $items = $invoice->items; @endphp
                            @if($items->count() > 1)
                                <ul class="mb-0 ps-3 list-unstyled">
                                    @foreach($items as $itm)
                                        <li><small>- {!! $itm->nama_item ? '<strong>'.e($itm->nama_item).'</strong>: ' : '' !!}{{ \Illuminate\Support\Str::limit($itm->deskripsi, 30) }}</small></li>
                                    @endforeach
                                </ul>
                            @elseif($items->count() == 1)
                                @php $itm = $items->first(); @endphp
                                {!! $itm->nama_item ? '<strong>'.e($itm->nama_item).'</strong><br>' : '' !!}{{ \Illuminate\Support\Str::limit($itm->deskripsi, 50) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                        <td class="text-nowrap">
                            @if($invoice->status == 'draft')
                                <span class="badge blink-draft" data-bs-toggle="modal" data-bs-target="#paidModal" data-id="{{ $invoice->id }}" data-nomor="{{ $invoice->nomor_invoice }}">Draft</span>
                            @elseif($invoice->status == 'dikirim')
                                <span class="badge bg-info">Dikirim</span>
                            @elseif($invoice->status == 'dibayar')
                                <span class="badge bg-success">Lunas</span>
                            @elseif($invoice->status == 'batal')
                                <span class="badge bg-danger">Batal</span>
                            @endif
                        </td>
                        <td class="text-center text-nowrap">
                            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info btn-sm text-white" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('invoices.export_pdf', $invoice->id) }}" class="btn btn-secondary btn-sm" title="Download PDF">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                            <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus invoice ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada data invoice.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $invoices->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@push('scripts')
<script>
let aiGeneratedData = null;
let mediaRecorder = null;
let audioChunks = [];
let isRecording = false;

function startRecording() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        document.getElementById('aiPrompt').value = 'Browser tidak mendukung voice. Silakan ketik manual.';
        return;
    }
    
    if (isRecording) { stopRecording(); return; }
    
    const btn = document.getElementById('micBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Dengar...';
    btn.classList.replace('btn-outline-danger', 'btn-danger');
    isRecording = true;
    audioChunks = [];
    
    navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (SpeechRecognition) {
            const recognition = new SpeechRecognition();
            recognition.lang = 'id-ID';
            recognition.continuous = true;
            recognition.interimResults = true;
            
            recognition.onresult = function(event) {
                let transcript = '';
                for (let i = event.resultIndex; i < event.results.length; i++) {
                    transcript += event.results[i][0].transcript;
                }
                document.getElementById('aiPrompt').value = transcript;
            };
            
            recognition.onerror = function() {
                stopRecording();
                document.getElementById('aiPrompt').value = 'Gagal mendeteksi suara. Silakan ketik manual.';
            };
            
            recognition.onend = function() { stopRecording(); };
            recognition.start();
            
            // Auto stop after 10 seconds of silence
            setTimeout(() => { recognition.stop(); }, 10000);
        } else {
            document.getElementById('aiPrompt').value = 'Browser tidak mendukung voice. Silakan ketik manual.';
            stopRecording();
        }
    }).catch(() => {
        document.getElementById('aiPrompt').value = 'Izin mic ditolak. Silakan ketik manual.';
        stopRecording();
    });
}

function stopRecording() {
    const btn = document.getElementById('micBtn');
    btn.innerHTML = '<i class="bi bi-mic"></i>';
    btn.classList.replace('btn-danger', 'btn-outline-danger');
    isRecording = false;
}

function generateWithAI() {
    const prompt = document.getElementById('aiPrompt').value.trim();
    if (!prompt) { alert('Silakan isi prompt atau gunakan voice terlebih dahulu.'); return; }
    
    const btn = document.getElementById('generateBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memproses...';
    
    fetch('{{ route("invoices.ai_generate") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ prompt })
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) { alert(data.error); return; }
        aiGeneratedData = data.data;
        document.getElementById('previewContent').innerHTML = data.html;
        const modal = bootstrap.Modal.getInstance(document.getElementById('aiModal'));
        modal.hide();
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    })
    .catch(err => { alert('Terjadi kesalahan: ' + err.message); })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-magic me-1"></i> Generate';
    });
}

function saveFromAI() {
    if (!aiGeneratedData || !aiGeneratedData.customer_id) {
        alert('Customer tidak ditemukan. Silakan buat invoice manual.');
        return;
    }
    
    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';
    
    fetch('{{ route("invoices.ai_store") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(aiGeneratedData)
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) { alert(data.error); return; }
        if (data.redirect) { window.location.href = data.redirect; }
    })
    .catch(err => { alert('Gagal menyimpan: ' + err.message); })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan Invoice';
    });
}

document.getElementById('aiModal').addEventListener('hidden.bs.modal', function() {
    stopRecording();
});
</script>
<script>
document.addEventListener('click', function (e) {
    var badge = e.target.closest('.blink-draft');
    if (!badge) return;
    var id = badge.dataset.id;
    var nomor = badge.dataset.nomor;
    document.getElementById('modalInvoiceInfo').textContent = 'Invoice: ' + nomor;
    document.getElementById('formBayar').action = '{{ route("invoices.mark_paid", "INVOICE_ID") }}'.replace('INVOICE_ID', id);
});
</script>
@endpush

@push('modals')
{{-- AI Input Modal --}}
<div class="modal fade" id="aiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-magic text-success me-2"></i>Bantu AI - Buat Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Gunakan voice atau ketik prompt untuk membuat invoice otomatis.</p>
                <div class="mb-3">
                    <label class="form-label">Prompt <span class="text-danger">*</span></label>
                    <textarea id="aiPrompt" class="form-control" rows="4" placeholder="Contoh: MCU 50 orang @100.000 untuk RSUD Sultan Thaha"></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" id="micBtn" class="btn btn-outline-danger" onclick="startRecording()" title="Voice Input">
                        <i class="bi bi-mic"></i>
                    </button>
                    <button type="button" id="generateBtn" class="btn btn-success flex-grow-1" onclick="generateWithAI()">
                        <i class="bi bi-magic me-1"></i> Generate
                    </button>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Klik mic 🎤 untuk voice input (Chrome/Edge). Cukup bicara natural.</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Preview Modal --}}
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-receipt text-primary me-2"></i>Preview Invoice - Hasil AI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <p class="text-muted text-center py-4">Memuat preview...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-pencil me-1"></i> Kembali Edit
                </button>
                <button type="button" id="saveBtn" class="btn btn-primary" onclick="saveFromAI()">
                    <i class="bi bi-check-lg me-1"></i> Simpan Invoice
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paidModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="" id="formBayar" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-check-circle text-success me-2"></i>Konfirmasi Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="modalInvoiceInfo" class="text-muted mb-3"></p>
                <div class="mb-3">
                    <label class="form-label">Tanggal Bayar <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_bayar" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Bukti Bayar <span class="text-danger">*</span></label>
                    <input type="file" name="bukti_bayar" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                    <div class="form-text">Format: JPG, PNG, atau PDF. Maksimal 2MB.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Tandai Lunas</button>
            </div>
        </form>
    </div>
</div>
@endpush
@endsection

@extends('layouts.admin')

@section('title', 'Daftar Penawaran')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 text-primary"><i class="bi bi-file-earmark-text me-2"></i>Daftar Penawaran (Quotation)</h5>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#aiModal">
                <i class="bi bi-magic me-1"></i> Bantu AI
            </button>
            <a href="{{ route('quotations.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Buat Penawaran
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('quotations.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nomor surat, perihal, atau nama customer..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="d-none d-sm-table-cell">No</th>
                        <th>Nomor Surat</th>
                        <th class="d-none d-sm-table-cell">Tanggal</th>
                        <th>Customer</th>
                        <th class="d-none d-sm-table-cell">Perihal</th>
                        <th class="d-none d-sm-table-cell">Total</th>
                        <th>Status</th>
                        <th width="18%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($quotations as $index => $quotation)
                    <tr>
                        <td class="d-none d-sm-table-cell">{{ $quotations->firstItem() + $index }}</td>
                        <td class="text-nowrap">{{ $quotation->nomor_surat }}</td>
                        <td class="d-none d-sm-table-cell">{{ date('d/m/Y', strtotime($quotation->tanggal)) }}</td>
                        <td class="text-nowrap">{{ $quotation->customer->nama_instansi }}</td>
                        <td class="d-none d-sm-table-cell">
                            @php $perihalArray = is_array($quotation->perihal) ? $quotation->perihal : (json_decode($quotation->perihal, true) ?? [$quotation->perihal]); @endphp
                            @if(count($perihalArray) > 1)
                                <ul class="mb-0 ps-3 list-unstyled">
                                    @foreach($perihalArray as $p)
                                        <li>- {{ \Illuminate\Support\Str::limit($p, 30) }}</li>
                                    @endforeach
                                </ul>
                            @else
                                {{ \Illuminate\Support\Str::limit($perihalArray[0], 50) }}
                            @endif
                        </td>
                        <td class="d-none d-sm-table-cell">Rp {{ number_format($quotation->total, 0, ',', '.') }}</td>
                        <td class="text-nowrap">
                            @if($quotation->status == 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @elseif($quotation->status == 'dikirim')
                                <span class="badge bg-info">Dikirim</span>
                            @elseif($quotation->status == 'deal')
                                <span class="badge bg-success">Deal</span>
                            @elseif($quotation->status == 'batal')
                                <span class="badge bg-danger">Batal</span>
                            @endif
                        </td>
                        <td class="text-center text-nowrap">
                            <a href="{{ route('quotations.show', $quotation->id) }}" class="btn btn-info btn-sm text-white" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('quotations.export_pdf', $quotation->id) }}" target="_blank" class="btn btn-secondary btn-sm" title="Export PDF">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                            <a href="{{ route('quotations.edit', $quotation->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('quotations.destroy', $quotation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus penawaran ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada data penawaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-end mt-3">
            {{ $quotations->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@push('scripts')
<script>
let aiGeneratedData = null;
let isRecording = false;

function voiceSupported() {
    return !!(window.SpeechRecognition || window.webkitSpeechRecognition);
}

function requestMicPermission() {
    return navigator.mediaDevices && navigator.mediaDevices.getUserMedia
        ? navigator.mediaDevices.getUserMedia({ audio: true }).then(s => { s.getTracks().forEach(t => t.stop()); return true; })
        : Promise.resolve(false);
}

let aiRecognition = null;

function startRecording() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) { return; }
    
    if (isRecording) { stopRecording(); return; }
    
    const btn = document.getElementById('micBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Izin mic...';
    btn.classList.replace('btn-outline-danger', 'btn-danger');
    
    requestMicPermission().then(granted => {
        if (!granted) {
            stopRecording();
            return;
        }
        
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Dengar...';
        isRecording = true;
        
        function startRec() {
            aiRecognition = new SpeechRecognition();
            aiRecognition.lang = 'id-ID';
            aiRecognition.continuous = true;
            aiRecognition.interimResults = true;
            
            aiRecognition.onresult = function(event) {
                let transcript = '';
                for (let i = event.resultIndex; i < event.results.length; i++) {
                    transcript += event.results[i][0].transcript;
                }
                document.getElementById('aiPrompt').value = transcript;
            };
            
            aiRecognition.onerror = function(event) {
                if (event.error === 'not-allowed' || event.error === 'permission-denied') {
                    stopRecording();
                    document.getElementById('aiPrompt').value = 'Izin mic ditolak. Silakan ketik manual.';
                } else if (event.error === 'no-speech' && isRecording) {
                    // silent restart on no-speech
                    setTimeout(() => { if (isRecording) startRec(); }, 300);
                } else if (isRecording) {
                    document.getElementById('aiPrompt').value = 'Gagal mendeteksi suara: ' + event.error + '. Silakan ketik manual.';
                }
            };
            
            aiRecognition.onend = function() {
                if (isRecording) startRec();
            };
            
            try { aiRecognition.start(); } catch(e) {}
        }
        
        startRec();
    }).catch(() => {
        stopRecording();
        document.getElementById('aiPrompt').value = 'Izin mic ditolak. Silakan ketik manual.';
    });
}

function stopRecording() {
    if (aiRecognition) { try { aiRecognition.stop(); } catch(e) {} aiRecognition = null; }
    const btn = document.getElementById('micBtn');
    btn.innerHTML = '<i class="bi bi-mic"></i>';
    btn.classList.replace('btn-danger', 'btn-outline-danger');
    isRecording = false;
}

document.getElementById('aiModal').addEventListener('shown.bs.modal', function() {
    document.getElementById('aiPrompt').focus();
    // restore saved provider
    const saved = localStorage.getItem('aiProvider') || '{{ config('services.ai.provider', 'gemini') }}';
    document.querySelector(`input[name="aiProvider"][value="${saved}"]`).checked = true;
});

document.querySelectorAll('input[name="aiProvider"]').forEach(el => {
    el.addEventListener('change', function() {
        if (this.checked) localStorage.setItem('aiProvider', this.value);
    });
});

function getSelectedProvider() {
    return document.querySelector('input[name="aiProvider"]:checked')?.value || '{{ config('services.ai.provider', 'gemini') }}';
}

function generateWithAI() {
    const prompt = document.getElementById('aiPrompt').value.trim();
    if (!prompt) { alert('Silakan isi prompt atau gunakan voice terlebih dahulu.'); return; }
    
    const btn = document.getElementById('generateBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memproses...';
    
    fetch('{{ route("quotations.ai_generate") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ prompt, provider: getSelectedProvider() })
    })
    .then(async r => {
        if (!r.ok) {
            let msg = 'Gagal memproses (kode ' + r.status + '). Coba lagi.';
            try { const d = await r.json(); if (d.error) msg = d.error; } catch(_) {}
            throw new Error(msg);
        }
        return r.json();
    })
    .then(data => {
        if (data.error) { alert(data.error); return; }
        aiGeneratedData = data.data;
        document.getElementById('previewContent').innerHTML = data.html;
        const modal = bootstrap.Modal.getInstance(document.getElementById('aiModal'));
        modal.hide();
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    })
    .catch(err => { alert('Gagal terhubung ke server. Periksa koneksi internet dan coba lagi.'); })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-magic me-1"></i> Generate';
    });
}

function saveFromAI() {
    if (!aiGeneratedData || !aiGeneratedData.customer_id) {
        alert('Customer tidak ditemukan. Silakan buat penawaran manual.');
        return;
    }
    
    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';
    
    fetch('{{ route("quotations.ai_store") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(aiGeneratedData)
    })
    .then(async r => {
        if (!r.ok) {
            let msg = 'Gagal menyimpan (kode ' + r.status + '). Coba lagi.';
            try { const d = await r.json(); if (d.error) msg = d.error; } catch(_) {}
            throw new Error(msg);
        }
        return r.json();
    })
    .then(data => {
        if (data.error) { alert(data.error); return; }
        if (data.redirect) { window.location.href = data.redirect; }
    })
    .catch(err => { alert(err.message || 'Gagal menyimpan penawaran. Coba lagi.'); })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan Penawaran';
    });
}

function editFromAI() {
    if (!aiGeneratedData || !aiGeneratedData.customer_id) {
        alert('Customer tidak ditemukan. Silakan buat penawaran manual.');
        return;
    }

    const btn = document.querySelector('#previewModal .btn-secondary');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan draft...';

    fetch('{{ route("quotations.ai_store_draft") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(aiGeneratedData)
    })
    .then(async r => {
        if (!r.ok) {
            let msg = 'Gagal menyimpan draft (kode ' + r.status + '). Coba lagi.';
            try { const d = await r.json(); if (d.error) msg = d.error; } catch(_) {}
            throw new Error(msg);
        }
        return r.json();
    })
    .then(data => {
        if (data.error) { alert(data.error); return; }
        if (data.redirect) { window.location.href = data.redirect; }
    })
    .catch(err => { alert(err.message || 'Gagal menyimpan draft. Coba lagi.'); })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-pencil me-1"></i> Kembali Edit';
    });
}
</script>
@endpush

@push('modals')
{{-- AI Input Modal --}}
<div class="modal fade" id="aiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-magic text-success me-2"></i>Bantu AI - Buat Penawaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Gunakan voice atau ketik prompt untuk membuat surat penawaran otomatis.</p>
                <div class="mb-3">
                    <label class="form-label">Prompt <span class="text-danger">*</span></label>
                    <textarea id="aiPrompt" class="form-control" rows="4" placeholder="Contoh: Penawaran 50 set seragam sekolah untuk RSUD Sultan Thaha"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">AI Provider</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="aiProvider" id="providerGemini" value="gemini" autocomplete="off">
                        <label class="btn btn-outline-success" for="providerGemini"><i class="bi bi-stars me-1"></i>Gemini</label>
                        <input type="radio" class="btn-check" name="aiProvider" id="providerOpenRouter" value="openrouter" autocomplete="off">
                        <label class="btn btn-outline-primary" for="providerOpenRouter"><i class="bi bi-box-arrow-up-right me-1"></i>OpenRouter</label>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="button" id="micBtn" class="btn btn-outline-danger w-100" onclick="startRecording()">
                        <i class="bi bi-mic"></i> Rekam Suara
                    </button>
                    <div class="form-text text-center">Klik untuk mulai / berhenti merekam. Hasil suara otomatis masuk ke prompt.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="generateBtn" class="btn btn-success flex-grow-1" onclick="generateWithAI()">
                    <i class="bi bi-magic me-1"></i> Generate
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Preview Modal --}}
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-text text-primary me-2"></i>Preview Penawaran - Hasil AI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <p class="text-muted">Sedang memproses...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="editFromAI()">
                    <i class="bi bi-pencil me-1"></i> Kembali Edit
                </button>
                <button type="button" id="saveBtn" class="btn btn-primary flex-grow-1" onclick="saveFromAI()">
                    <i class="bi bi-check-lg me-1"></i> Simpan Penawaran
                </button>
            </div>
        </div>
    </div>
</div>
@endpush
@endsection
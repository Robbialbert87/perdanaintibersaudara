<div class="row mb-4">
    <div class="col-md-6 mb-3 mb-md-0">
        <h6 class="text-muted mb-2">Informasi Penawaran</h6>
        <table class="table table-sm table-borderless info-table">
            <tr>
                <td width="130">Nomor Surat</td>
                <td width="10">:</td>
                <td><strong>#PREVIEW</strong></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ date('d/m/Y', strtotime($tanggal)) }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>:</td>
                <td><span class="badge bg-secondary">Draft</span></td>
            </tr>
            <tr>
                <td>Perihal Surat</td>
                <td>:</td>
                <td>{{ $perihal_surat ?: '-' }}</td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="text-muted mb-2">Tujuan (Customer)</h6>
        <table class="table table-sm table-borderless info-table">
            <tr>
                <td width="130">Instansi</td>
                <td width="10">:</td>
                <td>
                    <strong>{{ $customer->nama_instansi ?? $customer_name ?? '-' }}</strong>
                    @if(isset($foundCustomers) && $foundCustomers->count() > 1)
                        <br><small class="text-warning">Ditemukan {{ $foundCustomers->count() }} customer dengan nama mirip.</small>
                    @endif
                    @if(!$customer && !empty($customer_name))
                        <br><small class="text-danger">Customer "{{ $customer_name }}" tidak ditemukan.</small>
                    @endif
                </td>
            </tr>
            @if($customer)
            <tr>
                <td>Contact</td>
                <td>:</td>
                <td>{{ $customer->contact_person ?? '-' }}</td>
            </tr>
            <tr>
                <td>Telepon</td>
                <td>:</td>
                <td>{{ $customer->telepon ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $customer->alamat ?? '-' }} {{ $customer->kota ?? '' }}</td>
            </tr>
            @endif
        </table>
    </div>
</div>

@if(!empty($perihal) && is_array($perihal))
<h6 class="text-muted mb-3">Perihal / Kelompok Pekerjaan</h6>
<div class="mb-3">
    @foreach($perihal as $p)
        <span class="badge bg-light border text-dark me-1 mb-1">{{ $p }}</span>
    @endforeach
</div>
@endif

<h6 class="text-muted mb-3">Detail Item</h6>
<div class="table-responsive">
    <table class="table table-bordered align-middle" style="min-width:560px;">
        <thead class="table-light">
            <tr>
                <th width="5%">No</th>
                <th>Deskripsi Pekerjaan / Barang</th>
                <th width="8%" class="text-center">Vol</th>
                <th width="16%" class="text-end">Harga Satuan (Rp)</th>
                <th width="9%" class="text-center">Diskon</th>
                <th width="17%" class="text-end">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    @if(!empty($item['nama_item']))
                        <strong>{{ $item['nama_item'] }}</strong><br>
                    @endif
                    {{ $item['deskripsi'] ?? '' }}
                </td>
                <td class="text-center">{{ $item['volume'] ?? 1 }}</td>
                <td class="text-end">{{ number_format((float) ($item['harga_satuan'] ?? 0), 0, ',', '.') }}</td>
                <td class="text-center">
                    @if(!empty($item['diskon']))
                        {{ (float) $item['diskon'] }}%
                    @else
                        -
                    @endif
                </td>
                <td class="text-end">{{ number_format((float) ($item['subtotal'] ?? 0), 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="table-dark">
            <tr>
                <td colspan="5" class="text-end"><strong>TOTAL</strong></td>
                <td class="text-end"><strong>Rp {{ number_format((float) $total, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</div>

@if(!empty($catatan))
<div class="mt-4">
    <h6 class="text-muted mb-1">Catatan:</h6>
    <div class="p-3 bg-light rounded">
        {{ $catatan }}
    </div>
</div>
@endif

@if(!empty($kata_penutup))
<div class="mt-4">
    <h6 class="text-muted mb-1">Kata Penutup:</h6>
    <div class="p-3 bg-light rounded">
        {{ $kata_penutup }}
    </div>
</div>
@endif
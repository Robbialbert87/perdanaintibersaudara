<div class="row mb-4">
    <div class="col-md-6 mb-3 mb-md-0">
        <h6 class="text-muted mb-2">Informasi Invoice</h6>
        <table class="table table-sm table-borderless info-table">
            <tr>
                <td width="130">Nomor Invoice</td>
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

<h6 class="text-muted mb-3">Detail Item</h6>
<div class="table-responsive">
    <table class="table table-bordered align-middle" style="min-width:500px;">
        <thead class="table-light">
            <tr>
                <th width="5%">No</th>
                <th>Deskripsi Pekerjaan / Barang</th>
                <th width="8%" class="text-center">Vol</th>
                <th width="18%" class="text-end">Harga Satuan (Rp)</th>
                <th width="18%" class="text-end">Jumlah (Rp)</th>
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
                    @if(!empty($item['tanggal_kegiatan']))
                        <br><small class="text-muted">Tanggal: {{ \Carbon\Carbon::parse($item['tanggal_kegiatan'])->locale('id')->isoFormat('D MMMM YYYY') }}</small>
                    @endif
                </td>
                <td class="text-center">{{ $item['volume'] ?? 1 }}</td>
                <td class="text-end">{{ number_format((float) ($item['harga_satuan'] ?? 0), 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format((float) ($item['subtotal'] ?? 0), 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="table-dark">
            @if(!empty($ppn_active))
            @php
                $ppn = round((float) $total * 0.11);
                $grandTotal = (float) $total + $ppn;
            @endphp
            <tr>
                <td colspan="4" class="text-end">Sub Total</td>
                <td class="text-end">Rp {{ number_format((float) $total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-end">PPN (11%)</td>
                <td class="text-end">Rp {{ number_format($ppn, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-end"><strong>GRAND TOTAL</strong></td>
                <td class="text-end"><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
            </tr>
            @else
            <tr>
                <td colspan="4" class="text-end"><strong>TOTAL</strong></td>
                <td class="text-end"><strong>Rp {{ number_format((float) $total, 0, ',', '.') }}</strong></td>
            </tr>
            @endif
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

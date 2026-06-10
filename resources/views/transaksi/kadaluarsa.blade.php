@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3 shadow-sm border-0" role="alert" style="border-radius: 10px;">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@php
    $expiredCount = 0;
    $nearExpiredCount = 0;
    $now = \Carbon\Carbon::now();
    
    // Kelompokkan obat untuk monitoring (hanya yang memiliki tgl_kadaluarsa dan stok > 0)
    $monitoredObat = $obat->filter(function($o) {
        return !is_null($o->tgl_kadaluarsa) && $o->stok > 0;
    })->sortBy(function($o) {
        return $o->tgl_kadaluarsa;
    });

    foreach($monitoredObat as $o) {
        $kd = \Carbon\Carbon::parse($o->tgl_kadaluarsa);
        if ($kd->isPast()) {
            $expiredCount++;
        } elseif ($now->diffInDays($kd, false) <= 90) {
            $nearExpiredCount++;
        }
    }
@endphp

@if($expiredCount > 0 || $nearExpiredCount > 0)
    <div class="alert alert-danger shadow-sm border-0 d-flex align-items-center mt-3 animate__animated animate__headShake" role="alert" style="border-radius: 12px; background-color: #fff5f5; color: #e53e3e;">
        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
        <div>
            <strong style="font-size: 15px;">⚠️ Peringatan Kadaluarsa Ditemukan!</strong><br>
            Terdapat <span class="badge bg-danger" style="font-size: 12px; padding: 5px 8px;">{{ $expiredCount }}</span> obat yang <strong>Sudah Kadaluarsa</strong> dan 
            <span class="badge bg-warning text-dark" style="font-size: 12px; padding: 5px 8px;">{{ $nearExpiredCount }}</span> obat yang <strong>Mendekati Kadaluarsa</strong> (kurang dari 3 bulan). Mohon segera periksa fisik obat!
        </div>
    </div>
@endif

<!-- HEADER -->
<div class="header-blue">
    <h4 class="mb-1">BARANG KADALUARSA</h4>
    <small>Transaksi > Barang Kadaluarsa</small>
</div>

<!-- TABS NAVIGATION -->
<ul class="nav nav-pills mb-4" id="pills-tab" role="tablist" style="background: white; padding: 8px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: inline-flex;">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pills-monitoring-tab" data-bs-toggle="pill" data-bs-target="#pills-monitoring" type="button" role="tab" aria-controls="pills-monitoring" aria-selected="true">
            <i class="bi bi-shield-exclamation me-2"></i>Status & Monitoring Stok
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-log-tab" data-bs-toggle="pill" data-bs-target="#pills-log" type="button" role="tab" aria-controls="pills-log" aria-selected="false">
            <i class="bi bi-clock-history me-2"></i>Log Pembuangan Barang
        </button>
    </li>
</ul>

<!-- TABS CONTENT -->
<div class="tab-content" id="pills-tabContent">
    
    <!-- TAB 1: MONITORING STOK AKTIF -->
    <div class="tab-pane fade show active" id="pills-monitoring" role="tabpanel" aria-labelledby="pills-monitoring-tab">
        <div class="card-box">
            <div class="table-top mb-3">
                <div>
                    <h5>Status Kadaluarsa Stok Aktif</h5>
                    <small class="text-muted">Menampilkan daftar obat aktif yang memiliki catatan tanggal kadaluarsa.</small>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok Aktif</th>
                            <th>Satuan</th>
                            <th>Tanggal Kadaluarsa</th>
                            <th>Sisa Waktu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monitoredObat as $index => $item)
                            @php
                                $kdDate = \Carbon\Carbon::parse($item->tgl_kadaluarsa);
                                $daysRemaining = $now->diffInDays($kdDate, false);
                                $isExpired = $kdDate->isPast();
                                $isNear = !$isExpired && $daysRemaining <= 90;
                                
                                // Tentukan warna background kolom
                                $bgColor = '';
                                $badgeClass = 'bg-success';
                                $statusText = 'Aman';
                                
                                if ($isExpired) {
                                    $bgColor = 'background-color: #ffe8e8 !important; color: #b91c1c; font-weight: bold;';
                                    $badgeClass = 'bg-danger';
                                    $statusText = 'KADALUARSA';
                                } elseif ($isNear) {
                                    $bgColor = 'background-color: #fff3cd !important; color: #b45309; font-weight: bold;';
                                    $badgeClass = 'bg-warning text-dark';
                                    $statusText = '< 3 Bulan';
                                }
                            @endphp
                            <tr style="{{ $bgColor }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->kode }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->obat->kategori->nama ?? '-' }}</td>
                                <td>{{ $item->stok }}</td>
                                <td>{{ $item->obat->satuan->nama ?? '-' }}</td>
                                <td style="{{ $isExpired || $isNear ? 'border-left: 4px solid #ef4444;' : '' }}">
                                    {{ $kdDate->format('d M Y') }}
                                </td>
                                <td>
                                    @if($isExpired)
                                        <span class="text-danger">Lewat {{ abs($daysRemaining) }} hari</span>
                                    @else
                                        <span>{{ $daysRemaining }} hari lagi</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $badgeClass }}" style="font-size: 11px; padding: 5px 10px;">
                                        {{ $statusText }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Belum ada data tanggal kadaluarsa obat yang dicatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 2: LOG TRANSAKSI KADALUARSA (ASLI) -->
    <div class="tab-pane fade" id="pills-log" role="tabpanel" aria-labelledby="pills-log-tab">
        <div class="card-box">
            <!-- TOP -->
            <div class="table-top">
                <div>
                    <h5>Log Pencatatan Barang Dibuang/Kadaluarsa</h5>
                    <small>Catatan transaksi pengurangan stok akibat barang rusak atau kadaluarsa.</small>
                </div>
                <div class="top-action">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">+ TAMBAH DATA</button>
                </div>
            </div>

            <!-- TABLE -->
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Tanggal Dicatat</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($transaksi as $index => $item)
                    <tr>
                        <td>{{ $transaksi->firstItem() + $index }}</td>
                        <td>{{ $item->obat->kode ?? '-' }}</td>
                        <td>{{ $item->obat->nama ?? 'Obat Dihapus' }}</td>
                        <td>{{ $item->tanggal }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>{{ $item->obat->satuan ?? '-' }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>
                            <div class="aksi d-flex gap-1">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">✏ Edit</button>
                                <form action="/transaksi/{{ $item->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini? Stok akan dikembalikan.');" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">🗑 Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- FOOTER -->
            <div class="table-footer">
                <small>Menampilkan {{ $transaksi->firstItem() ?? 0 }} sampai {{ $transaksi->lastItem() ?? 0 }} dari {{ $transaksi->total() }} data</small>
                <div class="pagination">
                    {{ $transaksi->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/transaksi" method="POST">
                @csrf
                <input type="hidden" name="jenis" value="kadaluarsa">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang Kadaluarsa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Obat / Barang</label>
                        <select name="obat_id" class="form-control" required>
                            <option value="">Pilih Barang...</option>
                            @foreach($obat as $o)
                                <option value="{{ $o->id }}">{{ $o->kode }} - {{ $o->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Dicatat</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Jumlah Kadaluarsa</label>
                        <input type="number" name="jumlah" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" value="Barang Kadaluarsa">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Data -->
@foreach ($transaksi as $item)
<div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/transaksi/{{ $item->id }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="jenis" value="kadaluarsa">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Barang Kadaluarsa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Obat / Barang</label>
                        <select name="obat_id" class="form-control" required>
                            <option value="">Pilih Barang...</option>
                            @foreach($obat as $o)
                                <option value="{{ $o->id }}" {{ $item->obat_id == $o->id ? 'selected' : '' }}>{{ $o->kode }} - {{ $o->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Dicatat</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Jumlah Kadaluarsa</label>
                        <input type="number" name="jumlah" class="form-control" min="1" value="{{ $item->jumlah }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" value="{{ $item->keterangan }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<style>
    .nav-pills .nav-link {
        color: #64748b;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
        transition: all 0.25s ease;
    }
    .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
        background-color: #0d6efd;
        color: white;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.25);
    }
    .nav-pills .nav-link:hover:not(.active) {
        background-color: #f1f5f9;
        color: #0f172a;
    }
</style>

@endsection

@push('scripts')
<script src="{{ asset('js/transaksi.js') }}"></script>
@endpush
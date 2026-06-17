@extends('layouts.app')

@section('content')

<!-- HEADER -->
<div class="card p-4 mb-4"
     style="background: #007bff;
            color:white;
            border-radius:12px;">

    <h4>Halo, {{ auth()->user()->name }}.</h4>

    <p class="mb-0 font-weight-bold">
        Selamat datang di Sistem Informasi Supply Chain Management Toko Obat Templek Sehat.
    </p>

</div>

<!-- NOTIF STOK MENIPIS -->
@if(isset($stokMenipis) && $stokMenipis->count() > 0)
<div class="alert alert-warning alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px; border-left: 4px solid #f7b84b;">
    <div class="d-flex align-items-center">
        <i class="bi bi-exclamation-triangle-fill me-3" style="font-size: 1.5rem;"></i>
        <div>
            <strong>⚠️ Peringatan Stok Menipis!</strong>
            <span class="badge bg-warning text-dark ms-2">{{ $stokMenipis->count() }} obat</span>
            <div class="mt-1" style="font-size: 13px;">
                @foreach($stokMenipis->take(3) as $o)
                    <span class="me-3">{{ $o->nama }}: <strong>{{ $o->stok }}</strong> (min: {{ $o->stok_min }})</span>
                @endforeach
                @if($stokMenipis->count() > 3)
                    <span class="text-dark">+{{ $stokMenipis->count() - 3 }} lainnya</span>
                @endif
            </div>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- CARD UTAMA -->
<div class="row mb-4">

    <!-- TOTAL OBAT -->
    <div class="col-md-4 mb-3">
        <div class="card-custom d-flex align-items-center" style="background-color: #4a81d4; color: white; border-radius: 12px; padding: 25px;">
            <div class="me-4" style="font-size: 3rem; line-height: 1;">
                <i class="bi bi-capsule"></i>
            </div>
            <div>
                <h5 class="mb-1 fw-bold">Data Obat</h5>
                <p class="mb-0">{{ $totalObat }} Item</p>
            </div>
        </div>
    </div>

    <!-- OBAT MASUK -->
    <div class="col-md-4 mb-3">
        <div class="card-custom d-flex align-items-center" style="background-color: #f1556c; color: white; border-radius: 12px; padding: 25px;">
            <div class="me-4" style="font-size: 3rem; line-height: 1;">
                <i class="bi bi-box-arrow-in-right"></i>
            </div>
            <div>
                <h5 class="mb-1 fw-bold">Obat Masuk</h5>
                <p class="mb-0">{{ $obatMasuk }} Item</p>
            </div>
        </div>
    </div>

    <!-- OBAT KELUAR -->
    <div class="col-md-4 mb-3">
        <div class="card-custom d-flex align-items-center" style="background-color: #f7b84b; color: white; border-radius: 12px; padding: 25px;">
            <div class="me-4" style="font-size: 3rem; line-height: 1;">
                <i class="bi bi-box-arrow-left"></i>
            </div>
            <div>
                <h5 class="mb-1 fw-bold">Obat Keluar</h5>
                <p class="mb-0">{{ $obatKeluar }} Item</p>
            </div>
        </div>
    </div>

</div>

<!-- CARD KECIL -->
<div class="row mb-4">

    <div class="col-md-3 mb-3">
        <a href="/kategori" class="text-decoration-none d-block">
        <div class="card p-3 shadow-sm d-flex flex-row align-items-center border-0 dashboard-mini-card" style="border-radius: 10px; cursor: pointer; transition: all 0.25s;">
            <div class="me-3" style="font-size: 1.5rem; color: #4a81d4;">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold">Kategori Obat</h6>
                <small class="text-muted">{{ $kategori }} Item</small>
            </div>
        </div>
        </a>
    </div>

    <div class="col-md-3 mb-3">
        <a href="/jenis" class="text-decoration-none d-block">
        <div class="card p-3 shadow-sm d-flex flex-row align-items-center border-0 dashboard-mini-card" style="border-radius: 10px; cursor: pointer; transition: all 0.25s;">
            <div class="me-3" style="font-size: 1.5rem; color: #6f42c1;">
                <i class="bi bi-tags-fill"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold">Jenis Obat</h6>
                <small class="text-muted">{{ $jenis }} Item</small>
            </div>
        </div>
        </a>
    </div>

    <div class="col-md-3 mb-3">
        <a href="/satuan" class="text-decoration-none d-block">
        <div class="card p-3 shadow-sm d-flex flex-row align-items-center border-0 dashboard-mini-card" style="border-radius: 10px; cursor: pointer; transition: all 0.25s;">
            <div class="me-3" style="font-size: 1.5rem; color: #20c997;">
                <i class="bi bi-rulers"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold">Satuan Obat</h6>
                <small class="text-muted">{{ $satuan }} Item</small>
            </div>
        </div>
        </a>
    </div>

    <div class="col-md-3 mb-3">
        <a href="/supplier" class="text-decoration-none d-block">
        <div class="card p-3 shadow-sm d-flex flex-row align-items-center border-0 dashboard-mini-card" style="border-radius: 10px; cursor: pointer; transition: all 0.25s;">
            <div class="me-3" style="font-size: 1.5rem; color: #fd7e14;">
                <i class="bi bi-truck"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold">Data Supplier</h6>
                <small class="text-muted">{{ $totalSupplier }} Item</small>
            </div>
        </div>
        </a>
    </div>

</div>

<!-- GRAFIK + KADALUARSA -->
<div class="row mb-4">

    <!-- GRAFIK -->
    <div class="col-md-6 mb-3">
        <div class="card p-4 shadow-sm border-0 h-100" style="border-radius: 12px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="font-weight-bold mb-0 text-dark" style="font-weight: 700;">Pergerakan Stok Bulanan</h6>
                <div class="d-flex" style="font-size: 12px;">
                    <div class="d-flex align-items-center me-3">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background-color: #f1556c; display: inline-block; margin-right: 5px;"></span> Masuk
                    </div>
                    <div class="d-flex align-items-center">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background-color: #f7b84b; display: inline-block; margin-right: 5px;"></span> Keluar
                    </div>
                </div>
            </div>
            <div style="position: relative; height: 250px; width: 100%;">
                <canvas id="penjualanChart"
                        data-bulan='@json($bulan)'
                        data-masuk='@json($dataMasuk)'
                        data-keluar='@json($dataKeluar)'></canvas>
            </div>
        </div>
    </div>

    <!-- KADALUARSA -->
    <div class="col-md-6 mb-3">
        <div class="card p-4 shadow-sm border-0 h-100" style="border-radius: 12px;">
            <h6 class="font-weight-bold mb-4 text-dark" style="font-weight: 700;">Peringatan Obat Kadaluarsa</h6>
            <div class="table-responsive">
                <table class="table table-borderless table-striped mt-2 align-middle">
                    <thead style="background-color: #f3f4f6; color: #4b5563;">
                        <tr>
                            <th style="font-weight: 600; font-size: 13px;">Nama Obat</th>
                            <th style="font-weight: 600; font-size: 13px;">Sisa Batch</th>
                            <th style="font-weight: 600; font-size: 13px;">Tgl Expired</th>
                            <th style="font-weight: 600; font-size: 13px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kadaluarsa as $item)
                        <tr>
                            <td style="font-size: 14px;">{{ $item->obat->nama }}</td>
                            <td style="font-size: 14px;"><span class="badge bg-warning text-dark">{{ $item->stok }}</span></td>
                            <td style="font-size: 14px;">
                                {{ \Carbon\Carbon::parse($item->tgl_kadaluarsa)->format('d/m/y') }}
                            </td>
                            <td class="text-center">
                                <a href="/transaksi/kadaluarsa" class="btn btn-sm btn-danger text-white" style="border-radius: 6px; font-size: 12px; padding: 4px 10px; background-color: #f1556c; border: none;">Cek exp</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted" style="font-size: 14px;">
                                Tidak ada obat kadaluarsa
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- DATA STOCK OBAT -->
<div class="row">
    <div class="col-12">
        <div class="card p-4 shadow-sm border-0" style="border-radius: 12px;">

            <h6 class="font-weight-bold mb-4 text-dark" style="font-weight: 700;">Data Stock Obat</h6>

            <div class="table-responsive">
                <table class="table table-borderless table-striped mt-2 align-middle">
                    <thead style="background-color: #f3f4f6; color: #4b5563;">
                        <tr>
                            <th style="font-weight: 600; font-size: 13px;">Nama Obat</th>
                            <th style="font-weight: 600; font-size: 13px;">Kode Obat</th>
                            <th style="font-weight: 600; font-size: 13px;">Jenis Obat</th>
                            <th style="font-weight: 600; font-size: 13px;">Satuan</th>
                            <th style="font-weight: 600; font-size: 13px;">Stok</th>
                            <th style="font-weight: 600; font-size: 13px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockObat as $stock)
                        <tr>
                            <td style="font-size: 14px;">{{ $stock->nama }}</td>
                            <td style="font-size: 14px;">{{ $stock->kode }}</td>
                            <td style="font-size: 14px;">{{ $stock->jenis ? $stock->jenis->nama : '-' }}</td>
                            <td style="font-size: 14px;">{{ $stock->satuan ? $stock->satuan->nama : '-' }}</td>
                            <td style="font-size: 14px;">
                                <span class="badge {{ $stock->stok <= 0 ? 'bg-danger' : ($stock->stok_menipis ? 'bg-warning text-dark' : 'bg-success') }}">
                                    {{ $stock->stok }}
                                    @if($stock->stok_menipis)⚠@endif
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="/obat" class="btn btn-sm btn-success text-white" style="border-radius: 6px; font-size: 12px; padding: 4px 10px; background-color: #1abc9c; border: none;">Lihat</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="font-size: 14px;">
                                Tidak ada data stock obat
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/dashboard.js') }}?v={{ time() }}"></script>
@endpush

@endsection
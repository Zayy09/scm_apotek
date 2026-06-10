@extends('layouts.app')

@section('content')

<!-- HEADER -->
<div class="card p-4 mb-4"
     style="background: #007bff;
            color:white;
            border-radius:12px;">

    <h4>Halo, {{ auth()->user()->name }}.</h4>

    <p class="mb-0 font-weight-bold">
        Selamat datang di Web Sistem Informasi Apotek Templek Sehat!
    </p>

</div>

<!-- CARD UTAMA -->
<div class="row mb-4">

    <!-- TOTAL BARANG -->
    <div class="col-md-4 mb-3">
        <div class="card-custom d-flex align-items-center" style="background-color: #4a81d4; color: white; border-radius: 12px; padding: 25px;">
            <div class="me-4" style="font-size: 3rem; line-height: 1;">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <h5 class="mb-1 fw-bold">Data Barang</h5>
                <p class="mb-0">{{ $totalBarang }} Item</p>
            </div>
        </div>
    </div>

    <!-- BARANG MASUK -->
    <div class="col-md-4 mb-3">
        <div class="card-custom d-flex align-items-center" style="background-color: #f1556c; color: white; border-radius: 12px; padding: 25px;">
            <div class="me-4" style="font-size: 3rem; line-height: 1;">
                <i class="bi bi-box-arrow-in-right"></i>
            </div>
            <div>
                <h5 class="mb-1 fw-bold">Barang Masuk</h5>
                <p class="mb-0">{{ $barangMasuk }} Item</p>
            </div>
        </div>
    </div>

    <!-- BARANG KELUAR -->
    <div class="col-md-4 mb-3">
        <div class="card-custom d-flex align-items-center" style="background-color: #f7b84b; color: white; border-radius: 12px; padding: 25px;">
            <div class="me-4" style="font-size: 3rem; line-height: 1;">
                <i class="bi bi-box-arrow-left"></i>
            </div>
            <div>
                <h5 class="mb-1 fw-bold">Barang Keluar</h5>
                <p class="mb-0">{{ $barangKeluar }} Item</p>
            </div>
        </div>
    </div>

</div>

<!-- CARD KECIL -->
<div class="row mb-4">

    <div class="col-md-3 mb-3">
        <div class="card p-3 shadow-sm d-flex flex-row align-items-center border-0" style="border-radius: 10px;">
            <div class="me-3" style="font-size: 1.5rem; color: #4a81d4;">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold">Kategori Barang</h6>
                <small class="text-muted">{{ $kategori }} Item</small>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card p-3 shadow-sm d-flex flex-row align-items-center border-0" style="border-radius: 10px;">
            <div class="me-3" style="font-size: 1.5rem; color: #4a81d4;">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold">Jenis Barang</h6>
                <small class="text-muted">{{ $jenis }} Item</small>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card p-3 shadow-sm d-flex flex-row align-items-center border-0" style="border-radius: 10px;">
            <div class="me-3" style="font-size: 1.5rem; color: #4a81d4;">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold">Satuan Barang</h6>
                <small class="text-muted">{{ $satuan }} Item</small>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card p-3 shadow-sm d-flex flex-row align-items-center border-0" style="border-radius: 10px;">
            <div class="me-3" style="font-size: 1.5rem; color: #4a81d4;">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold">Data User</h6>
                <small class="text-muted">{{ \App\Models\User::count() }} Item</small>
            </div>
        </div>
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

            <h6 class="font-weight-bold mb-4 text-dark" style="font-weight: 700;">Peringatan Barang Kadaluarsa</h6>

            <div class="table-responsive">
                <table class="table table-borderless table-striped mt-2 align-middle">
                    <thead style="background-color: #f3f4f6; color: #4b5563;">
                        <tr>
                            <th style="font-weight: 600; font-size: 13px;">Nama Barang</th>
                            <th style="font-weight: 600; font-size: 13px;">Kode Barang</th>
                            <th style="font-weight: 600; font-size: 13px;">Tgl Expired</th>
                            <th style="font-weight: 600; font-size: 13px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kadaluarsa as $item)
                        <tr>
                            <td style="font-size: 14px;">{{ $item->nama }}</td>
                            <td style="font-size: 14px;">{{ $item->kode }}</td>
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
                                Tidak ada barang kadaluarsa
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</div>

<!-- DATA STOCK BARANG -->
<div class="row">
    <div class="col-12">
        <div class="card p-4 shadow-sm border-0" style="border-radius: 12px;">
            
            <h6 class="font-weight-bold mb-4 text-dark" style="font-weight: 700;">Data Stock Barang</h6>

            <div class="table-responsive">
                <table class="table table-borderless table-striped mt-2 align-middle">
                    <thead style="background-color: #f3f4f6; color: #4b5563;">
                        <tr>
                            <th style="font-weight: 600; font-size: 13px;">Nama Barang</th>
                            <th style="font-weight: 600; font-size: 13px;">Kode Barang</th>
                            <th style="font-weight: 600; font-size: 13px;">Jenis Barang</th>
                            <th style="font-weight: 600; font-size: 13px;">Satuan Barang</th>
                            <th style="font-weight: 600; font-size: 13px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockBarang as $stock)
                        <tr>
                            <td style="font-size: 14px;">{{ $stock->nama }}</td>
                            <td style="font-size: 14px;">{{ $stock->kode }}</td>
                            <td style="font-size: 14px;">{{ $stock->jenis ? $stock->jenis->nama : '-' }}</td>
                            <td style="font-size: 14px;">{{ $stock->stok }} {{ $stock->satuan ? $stock->satuan->nama : '-' }}</td>
                            <td class="text-center">
                                <a href="/obat" class="btn btn-sm btn-success text-white" style="border-radius: 6px; font-size: 12px; padding: 4px 10px; background-color: #1abc9c; border: none;">Lihat</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="font-size: 14px;">
                                Tidak ada data stock barang
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
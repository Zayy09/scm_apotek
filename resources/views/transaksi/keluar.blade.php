@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('stok_menipis'))
    <div class="alert alert-warning alert-dismissible fade show mt-2" role="alert" style="border-radius: 10px;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('stok_menipis') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- HEADER -->
<div class="header-blue">
    <h4 class="mb-1">OBAT KELUAR</h4>
    <small>Transaksi > Obat Keluar</small>
</div>

<!-- CARD -->
<div class="card-box">

    <!-- TOP -->
    <div class="table-top">
        <div>
            <h5>Data Obat Keluar</h5>
            <small>
                Tampilkan
                <select>
                    <option>10</option>
                </select>
                Data
            </small>
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
                <th>Nama Obat</th>
                <th>Tanggal Keluar</th>
                <th>Satuan</th>
                <th>Jmlh Keluar</th>
                <th>Sisa Stok (Saat Ini)</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($transaksi as $index => $item)
            <tr>
                <td>{{ $transaksi->firstItem() + $index }}</td>
                <td>{{ $item->obat->nama }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->obat->satuan->nama ?? '-' }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>
                    @php $sisaStok = $item->obat->stok; $stokMin = $item->obat->stok_min; @endphp
                    <span class="badge {{ $sisaStok <= 0 ? 'bg-danger' : ($stokMin > 0 && $sisaStok <= $stokMin ? 'bg-warning text-dark' : 'bg-success') }}">
                        {{ $sisaStok }}
                        @if($stokMin > 0 && $sisaStok <= $stokMin && $sisaStok > 0)
                            <i class="bi bi-exclamation-triangle-fill ms-1"></i>
                        @endif
                    </span>
                </td>
                <td>{{ $item->keterangan }}</td>

                <td class="aksi">
                    <div style="display:flex; gap:6px;">
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

<!-- Modal Tambah Data -->
<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/transaksi" method="POST">
                @csrf
                <input type="hidden" name="jenis" value="keluar">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Obat Keluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Obat <span class="text-danger">*</span></label>
                        <select name="obat_id" class="form-control" required>
                            <option value="">Pilih Obat...</option>
                            @foreach($obat as $o)
                                <option value="{{ $o->id }}">{{ $o->kode }} - {{ $o->nama }} (Stok: {{ $o->stok }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Keluar <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Jumlah Keluar <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control">
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
                <input type="hidden" name="jenis" value="keluar">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Obat Keluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Obat</label>
                        <select name="obat_id" class="form-control" required>
                            <option value="">Pilih Obat...</option>
                            @foreach($obat as $o)
                                <option value="{{ $o->id }}" {{ $item->obat_id == $o->id ? 'selected' : '' }}>{{ $o->kode }} - {{ $o->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Keluar</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Jumlah Keluar</label>
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

@endsection

@push('scripts')
<script src="{{ asset('js/transaksi.js') }}"></script>
@endpush
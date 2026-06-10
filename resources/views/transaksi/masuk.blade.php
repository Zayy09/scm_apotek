@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- HEADER -->
<div class="header-blue">
    <h4 class="mb-1">BARANG MASUK</h4>
    <small>Transaksi > Barang Masuk</small>
</div>

<!-- CARD -->
<div class="card-box">

    <!-- TOP -->
    <div class="table-top">
        <div>
            <h5>Data Barang Masuk</h5>
            <small>
                Tampilkan 
                <select>
                    <option>6</option>
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
                <th>Nama Barang</th>
                <th>Tanggal Masuk</th>
                <th>Satuan</th>
                <th>Jmlh Masuk</th>
                <th>Total Stok (Saat Ini)</th>
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
                <td>{{ $item->obat->stok }}</td>
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
                <input type="hidden" name="jenis" value="masuk">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang Masuk</h5>
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
                        <label>Tanggal Masuk</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Jumlah Masuk</label>
                        <input type="number" name="jumlah" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Kadaluarsa</label>
                        <input type="date" name="tgl_kadaluarsa" class="form-control">
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
                <input type="hidden" name="jenis" value="masuk">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Barang Masuk</h5>
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
                        <label>Tanggal Masuk</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Jumlah Masuk</label>
                        <input type="number" name="jumlah" class="form-control" min="1" value="{{ $item->jumlah }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Kadaluarsa</label>
                        <input type="date" name="tgl_kadaluarsa" class="form-control" value="{{ $item->obat->tgl_kadaluarsa }}">
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
@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- HEADER -->
    <div class="header-blue">
        <h4>SATUAN OBAT</h4>
        <small>Master > Stock Obat > Data Satuan</small>
    </div>

    <!-- CARD -->
    <div class="card-box">

        <!-- TOP -->
        <div class="table-top">
            <div>
                <h5>Data Satuan Obat</h5>
                <small>
                    Tampilkan
                    <select>
                        <option>10</option>
                    </select> Data
                </small>
            </div>

            <div class="top-action d-flex gap-2">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">+ TAMBAH DATA</button>
                <form action="/satuan" method="GET" class="d-flex">
                    <input type="text" name="nama" class="form-control" placeholder="Filter by Satuan" value="{{ request('nama') }}">
                    <button type="submit" class="btn btn-secondary ms-1">Cari</button>
                </form>
            </div>
        </div>

        <!-- TABLE -->
        <table class="table-custom">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th>Satuan Obat</th>
                    <th>Stok Minimum</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($satuan as $index => $item)
                <tr>
                    <td>{{ $satuan->firstItem() + $index }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>
                        @if($item->stok_min > 0)
                            <span class="badge bg-warning text-dark">{{ $item->stok_min }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="aksi d-flex gap-1">
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">✏ Edit</button>
                        <form action="/satuan/{{ $item->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑 Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- FOOTER -->
        <div class="table-footer">
            <small>Menampilkan {{ $satuan->firstItem() ?? 0 }} sampai {{ $satuan->lastItem() ?? 0 }} dari {{ $satuan->total() }} data</small>
            <div class="pagination">
                {{ $satuan->links() }}
            </div>
        </div>

    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="tambahModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/satuan" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Satuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Satuan <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Stok Minimum</label>
                            <input type="number" name="stok_min" class="form-control" min="0" value="0">
                            <small class="text-muted">Notifikasi stok menipis akan muncul jika stok obat mencapai nilai ini. Isi 0 untuk menonaktifkan.</small>
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
    @foreach ($satuan as $item)
    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/satuan/{{ $item->id }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data Satuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Satuan <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" value="{{ $item->nama }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Stok Minimum</label>
                            <input type="number" name="stok_min" class="form-control" min="0" value="{{ $item->stok_min }}">
                            <small class="text-muted">Notifikasi stok menipis akan muncul jika stok obat mencapai nilai ini. Isi 0 untuk menonaktifkan.</small>
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

</div>

@endsection

@push('scripts')
<script src="{{ asset('js/obat_satuan.js') }}"></script>
@endpush
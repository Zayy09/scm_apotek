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
        <h4>JENIS OBAT</h4>
        <small>Master > Stock Obat > Data Jenis</small>
    </div>

    <!-- CARD -->
    <div class="card-box">

        <!-- TOP -->
        <div class="table-top">
            <div>
                <h5>Data Jenis Obat</h5>
                <small>
                    Tampilkan 
                    <select>
                        <option>6</option>
                    </select> Data
                </small>
            </div>

            <div class="top-action d-flex gap-2">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">+ TAMBAH DATA</button>
                <form action="/jenis" method="GET" class="d-flex">
                    <input type="text" name="nama" class="form-control" placeholder="Filter by Jenis" value="{{ request('nama') }}">
                    <button type="submit" class="btn btn-secondary ms-1">Cari</button>
                </form>
            </div>
        </div>

        <!-- TABLE -->
        <table class="table-custom">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th>Jenis Obat</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($jenis as $index => $item)
                <tr>
                    <td>{{ $jenis->firstItem() + $index }}</td>
                    <td>{{ $item->nama }}</td>
                    <td class="aksi d-flex gap-1 justify-content-end">
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">✏ Edit</button>
                        <form action="/jenis/{{ $item->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑 Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- FOOTER -->
        <div class="table-footer">
            <small>Menampilkan {{ $jenis->firstItem() ?? 0 }} sampai {{ $jenis->lastItem() ?? 0 }} dari {{ $jenis->total() }} data</small>

            <div class="pagination">
                {{ $jenis->links() }}
            </div>
        </div>

    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="tambahModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/jenis" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Jenis</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Nama Jenis</label>
                            <input type="text" name="nama" class="form-control" required>
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
    @foreach ($jenis as $item)
    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/jenis/{{ $item->id }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data Jenis</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Jenis</label>
                            <input type="text" name="nama" class="form-control" value="{{ $item->nama }}" required>
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
<script src="{{ asset('js/obat_jenis.js') }}"></script>
@endpush
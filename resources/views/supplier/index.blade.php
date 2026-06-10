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
        <h4>DATA SUPPLIER</h4>
        <small>Master > Data Supplier</small>
    </div>

    <!-- CARD -->
    <div class="card-box">

        <!-- TOP -->
        <div class="table-top">
            <div>
                <h5>Data Supplier</h5>
                <small>
                    Tampilkan 
                    <select>
                        <option>10</option>
                    </select> Data
                </small>
            </div>

            <div class="top-action d-flex gap-2">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">+ TAMBAH DATA</button>
                <form action="/supplier" method="GET" class="d-flex">
                    <input type="text" name="nama" class="form-control" placeholder="Filter by Supplier" value="{{ request('nama') }}">
                    <button type="submit" class="btn btn-secondary ms-1">Cari</button>
                </form>
            </div>
        </div>

        <!-- TABLE -->
        <table class="table-custom">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th>ID Supplier</th>
                    <th>Nama Supplier</th>
                    <th>No Telepon</th>
                    <th>E-mail</th>
                    <th>Alamat</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($supplier as $index => $item)
                <tr>
                    <td>{{ $supplier->firstItem() + $index }}</td>
                    <td>{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->no_telepon }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td class="aksi d-flex gap-1">
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">✏ Edit</button>
                        <form action="/supplier/{{ $item->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑 Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- FOOTER -->
        <div class="table-footer">
            <small>Menampilkan {{ $supplier->firstItem() ?? 0 }} sampai {{ $supplier->lastItem() ?? 0 }} dari {{ $supplier->total() }} data</small>

            <div class="pagination">
                {{ $supplier->links() }}
            </div>
        </div>

    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="tambahModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/supplier" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Supplier</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>No Telepon</label>
                            <input type="text" name="no_telepon" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>E-mail</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3"></textarea>
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
    @foreach ($supplier as $item)
    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/supplier/{{ $item->id }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Supplier</label>
                            <input type="text" name="nama" class="form-control" value="{{ $item->nama }}" required>
                        </div>
                        <div class="mb-3">
                            <label>No Telepon</label>
                            <input type="text" name="no_telepon" class="form-control" value="{{ $item->no_telepon }}">
                        </div>
                        <div class="mb-3">
                            <label>E-mail</label>
                            <input type="email" name="email" class="form-control" value="{{ $item->email }}">
                        </div>
                        <div class="mb-3">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3">{{ $item->alamat }}</textarea>
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
<script src="{{ asset('js/supplier.js') }}"></script>
@endpush
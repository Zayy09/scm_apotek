@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('stok_menipis'))
        <div class="alert alert-warning alert-dismissible fade show mt-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('stok_menipis') }}
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
                    <th>Obat yang Dijual</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($supplier as $index => $item)
                <tr>
                    <td>{{ $supplier->firstItem() + $index }}</td>
                    <td>{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->no_telepon ?? '-' }}</td>
                    <td>{{ $item->email ?? '-' }}</td>
                    <td>{{ $item->alamat ?? '-' }}</td>
                    <td>
                        @if($item->obat->count() > 0)
                            <span class="badge bg-primary" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#detailObatModal{{ $item->id }}">
                                {{ $item->obat->count() }} obat
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="aksi d-flex gap-1">
                        <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#detailObatModal{{ $item->id }}">🔍 Detail</button>
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
                    <td colspan="8" style="text-align: center;">Tidak ada data</td>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="/supplier" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Nama Supplier <span class="text-danger">*</span></label>
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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Obat yang Dijual Supplier Ini</label>
                                    <small class="text-muted d-block mb-2">Pilih obat-obat yang disuplai oleh supplier ini.</small>
                                    <div style="max-height: 280px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 6px; padding: 10px;">
                                        @foreach($obatList as $o)
                                        <div class="form-check mb-1">
                                            <input class="form-check-input" type="checkbox" name="obat_ids[]" value="{{ $o->id }}" id="tambah_obat_{{ $o->id }}">
                                            <label class="form-check-label" for="tambah_obat_{{ $o->id }}">
                                                <span class="badge bg-secondary me-1">{{ $o->kode }}</span> {{ $o->nama }}
                                                @if($o->supplier_id && $o->supplier_id != null)
                                                    <small class="text-warning">(sdh di supplier lain)</small>
                                                @endif
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
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

    <!-- Modal Edit Data & Modal Detail Obat -->
    @foreach ($supplier as $item)

    <!-- Modal Detail Obat -->
    <div class="modal fade" id="detailObatModal{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: #4a81d4; color: white;">
                    <h5 class="modal-title">🏭 Detail Obat — {{ $item->nama }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nama Supplier:</strong> {{ $item->nama }}</p>
                            <p class="mb-1"><strong>Telepon:</strong> {{ $item->no_telepon ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Email:</strong> {{ $item->email ?? '-' }}</p>
                            <p class="mb-1"><strong>Alamat:</strong> {{ $item->alamat ?? '-' }}</p>
                        </div>
                    </div>
                    <hr>
                    <h6 class="fw-bold mb-3">Daftar Obat yang Dibeli dari Supplier Ini:</h6>
                    @if($item->obat->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-bordered">
                            <thead style="background-color: #f3f4f6;">
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Obat</th>
                                    <th>Kategori</th>
                                    <th>Jenis</th>
                                    <th>Satuan</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->obat as $idx => $o)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td><span class="badge bg-secondary">{{ $o->kode }}</span></td>
                                    <td>{{ $o->nama }}</td>
                                    <td>{{ $o->kategori->nama ?? '-' }}</td>
                                    <td>{{ $o->jenis->nama ?? '-' }}</td>
                                    <td>{{ $o->satuan->nama ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $o->stok <= 0 ? 'bg-danger' : ($o->stok_menipis ? 'bg-warning text-dark' : 'bg-success') }}">
                                            {{ $o->stok }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <p class="text-muted text-center py-3">Belum ada obat yang ditautkan ke supplier ini.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Data -->
    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="/supplier/{{ $item->id }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Nama Supplier <span class="text-danger">*</span></label>
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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Obat yang Dijual Supplier Ini</label>
                                    <small class="text-muted d-block mb-2">Centang obat-obat yang disuplai supplier ini.</small>
                                    <div style="max-height: 280px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 6px; padding: 10px;">
                                        @foreach($obatList as $o)
                                        <div class="form-check mb-1">
                                            <input class="form-check-input" type="checkbox" name="obat_ids[]" value="{{ $o->id }}"
                                                id="edit_obat_{{ $item->id }}_{{ $o->id }}"
                                                {{ $o->supplier_id == $item->id ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_obat_{{ $item->id }}_{{ $o->id }}">
                                                <span class="badge bg-secondary me-1">{{ $o->kode }}</span> {{ $o->nama }}
                                                @if($o->supplier_id && $o->supplier_id != $item->id)
                                                    <small class="text-warning">(sdh di supplier lain)</small>
                                                @endif
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
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
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
        <h4>DATA OBAT</h4>
        <small>Beranda > Master > Data Obat</small>
    </div>

    <!-- CARD -->
    <div class="card-box">

        <!-- TOP -->
        <div class="table-top">
            <div>
                <h5>Data Obat</h5>
                <small>
                    Tampilkan
                    <select>
                        <option>10</option>
                    </select> Data
                </small>
            </div>

            <div class="top-action d-flex gap-2">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">+ TAMBAH DATA</button>
                <form action="/obat" method="GET" class="d-flex">
                    <input type="text" name="kategori" class="form-control" placeholder="Filter by Kategori" value="{{ request('kategori') }}">
                    <button type="submit" class="btn btn-secondary ms-1">Cari</button>
                </form>
            </div>
        </div>

        <!-- TABLE -->
        <table class="table-custom">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Obat</th>
                    <th>Nama Obat</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Supplier</th>
                    <th>Obat Masuk</th>
                    <th>Obat Keluar</th>
                    <th>Stok Sisa</th>
                    <th>Stok Min</th>
                    <th>Satuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($obat as $index => $item)
                <tr>
                    <td>{{ $obat->firstItem() + $index }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->kategori->nama ?? '-' }}</td>
                    <td>{{ $item->jenis->nama ?? '-' }}</td>
                    <td>{{ $item->supplier->nama ?? '-' }}</td>
                    <td>{{ $item->transaksi->where('jenis', 'masuk')->sum('jumlah') }}</td>
                    <td>{{ $item->transaksi->whereIn('jenis', ['keluar','kadaluarsa'])->sum('jumlah') }}</td>
                    <td>
                        <span class="badge {{ $item->stok <= 0 ? 'bg-danger' : ($item->stok_menipis ? 'bg-warning text-dark' : 'bg-success') }}">
                            {{ $item->stok }}
                        </span>
                    </td>
                    <td>
                        @php $stokMin = $item->satuan->stok_min ?? 0; @endphp
                        @if($stokMin > 0)
                            <span class="badge bg-secondary">{{ $stokMin }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $item->satuan->nama ?? '-' }}</td>
                    <td class="aksi d-flex gap-1">
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">✏ Edit</button>
                        <form action="/obat/{{ $item->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑 Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" style="text-align: center;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- FOOTER -->
        <div class="table-footer">
            <small>Menampilkan {{ $obat->firstItem() ?? 0 }} sampai {{ $obat->lastItem() ?? 0 }} dari {{ $obat->total() }} data</small>
            <div class="pagination">
                {{ $obat->links() }}
            </div>
        </div>

    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="tambahModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/obat" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Obat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3" style="display: none;">
                            <label>ID Obat (Kode)</label>
                            <input type="text" name="kode" class="form-control" value="AUTO" readonly>
                        </div>
                        <div class="mb-3">
                            <label>Nama Obat <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Kategori <span class="text-danger">*</span></label>
                            <select name="kategori_id" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoriList as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Jenis <span class="text-danger">*</span></label>
                            <select name="jenis_id" class="form-control" required>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($jenisList as $j)
                                    <option value="{{ $j->id }}">{{ $j->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Satuan <span class="text-danger">*</span></label>
                            <select name="satuan_id" class="form-control" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($satuanList as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama }}{{ $s->stok_min > 0 ? ' (min: '.$s->stok_min.')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Supplier</label>
                            <select name="supplier_id" class="form-control">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($supplierList as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->nama }}</option>
                                @endforeach
                            </select>
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
    @foreach ($obat as $item)
    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/obat/{{ $item->id }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data Obat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>ID Obat (Kode)</label>
                            <input type="text" name="kode" class="form-control" value="{{ $item->kode }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label>Nama Obat <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" value="{{ $item->nama }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Kategori</label>
                            <select name="kategori_id" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoriList as $k)
                                    <option value="{{ $k->id }}" {{ $item->kategori_id == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Jenis</label>
                            <select name="jenis_id" class="form-control" required>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($jenisList as $j)
                                    <option value="{{ $j->id }}" {{ $item->jenis_id == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Stok Saat Ini</label>
                            <input type="number" class="form-control" value="{{ $item->stok }}" readonly disabled
                                style="background:#f0f0f0; cursor:not-allowed;">
                            <small class="text-muted">Stok dihitung otomatis dari obat masuk &minus; keluar</small>
                        </div>
                        <div class="mb-3">
                            <label>Satuan</label>
                            <select name="satuan_id" class="form-control" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($satuanList as $s)
                                    <option value="{{ $s->id }}" {{ $item->satuan_id == $s->id ? 'selected' : '' }}>
                                        {{ $s->nama }}{{ $s->stok_min > 0 ? ' (min: '.$s->stok_min.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Supplier</label>
                            <select name="supplier_id" class="form-control">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($supplierList as $sup)
                                    <option value="{{ $sup->id }}" {{ $item->supplier_id == $sup->id ? 'selected' : '' }}>{{ $sup->nama }}</option>
                                @endforeach
                            </select>
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
<script src="{{ asset('js/obat_data.js') }}"></script>
@endpush
@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <!-- HEADER -->
    <div class="header-blue">
        <h4>LAPORAN OBAT MASUK</h4>
        <small>Master > Laporan Obat > Laporan Obat Masuk</small>
    </div>

    <!-- FILTER -->
    <div class="card-box mb-3">
        <h5 style="margin-bottom:15px;">Filter Data Obat</h5>

        <form action="/laporan/masuk" method="GET">
            <div class="filter-box">
                <div>
                    <label>Tanggal Awal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>

                <div>
                    <label>Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>

                <div class="filter-action d-flex gap-2">
                    <button type="submit" class="btn btn-primary">👁 TAMPILKAN</button>
                    <a href="{{ url('/laporan/masuk/export') }}?{{ http_build_query(request()->all()) }}" class="btn btn-success">📊 EXPORT EXCEL</a>
                </div>
            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div class="card-box">

        <div class="table-top">
            <div>
                <h5>Data Laporan Obat Masuk</h5>
                <small>
                    Tampilkan 
                    <select>
                        <option>10</option>
                    </select> Data
                </small>
            </div>

            <a href="/transaksi/masuk" class="btn btn-primary">+ TAMBAH DATA</a>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Obat</th>
                    <th>Nama Obat</th>
                    <th>Tanggal Masuk</th>
                    <th>Satuan</th>
                    <th>Jumlah Masuk</th>
                    <th>Keterangan</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($transaksi as $index => $item)
                <tr>
                    <td>{{ $transaksi->firstItem() + $index }}</td>
                    <td>{{ $item->obat->kode ?? '-' }}</td>
                    <td>{{ $item->obat->nama ?? 'Obat Dihapus' }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->obat->satuan->nama ?? '-' }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ $item->keterangan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data laporan</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- FOOTER -->
        <div class="table-footer mt-3">
            <small>Menampilkan {{ $transaksi->firstItem() ?? 0 }} sampai {{ $transaksi->lastItem() ?? 0 }} dari {{ $transaksi->total() }} data</small>

            <div class="pagination">
                {{ $transaksi->links() }}
            </div>
        </div>

    </div>

</div>

@endsection
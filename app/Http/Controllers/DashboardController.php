<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Transaksi;
use App\Models\Kategori;
use App\Models\Jenis;
use App\Models\Satuan;
use App\Models\Supplier;

class DashboardController extends Controller
{
    public function index()
    {
        $year = date('Y');

        $transaksiMasuk = Transaksi::where('jenis', 'masuk')
            ->whereYear('tanggal', $year)
            ->selectRaw('MONTH(tanggal) as month, SUM(jumlah) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $transaksiKeluar = Transaksi::where('jenis', 'keluar')
            ->whereYear('tanggal', $year)
            ->selectRaw('MONTH(tanggal) as month, SUM(jumlah) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $bulan      = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataMasuk  = [];
        $dataKeluar = [];

        for ($i = 1; $i <= 12; $i++) {
            $dataMasuk[]  = $transaksiMasuk[$i]  ?? 0;
            $dataKeluar[] = $transaksiKeluar[$i] ?? 0;
        }

        // Obat yang stoknya menipis (stok <= stok_min satuan, dan stok_min > 0)
        $semuaObat   = Obat::with(['satuan', 'transaksi', 'jenis'])->get();
        $stokMenipis = $semuaObat->filter(function ($o) {
            $stokMin = $o->satuan ? ($o->satuan->stok_min ?? 0) : 0;
            return $stokMin > 0 && $o->stok <= $stokMin;
        });

        return view('dashboard', [
            'totalObat'    => Obat::count(),
            'obatMasuk'    => Transaksi::where('jenis', 'masuk')->sum('jumlah'),
            'obatKeluar'   => Transaksi::where('jenis', 'keluar')->sum('jumlah'),

            'kategori'      => Kategori::count(),
            'jenis'         => Jenis::count(),
            'satuan'        => Satuan::count(),
            'totalSupplier' => Supplier::count(),

            'kadaluarsa'   => \App\Models\ObatBatch::with('obat')
                                ->where('stok', '>', 0)
                                ->where('tgl_kadaluarsa', '<=', now()->addDays(30))
                                ->get(),
            'stokMenipis'  => $stokMenipis,

            // For Chart
            'bulan'      => $bulan,
            'dataMasuk'  => $dataMasuk,
            'dataKeluar' => $dataKeluar,

            // For Data Stock Obat Table
            'stockObat' => Obat::with(['jenis', 'satuan', 'transaksi'])->orderBy('created_at', 'desc')->take(5)->get(),
        ]);
    }
}
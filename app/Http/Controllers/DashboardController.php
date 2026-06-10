<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Transaksi;
use App\Models\Kategori;
use App\Models\Jenis;
use App\Models\Satuan;

class DashboardController extends Controller
{   
    public function index()
    {
        // Monthly Transaction Data for Chart (Current Year)
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

        $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataMasuk = [];
        $dataKeluar = [];

        for ($i = 1; $i <= 12; $i++) {
            $dataMasuk[] = $transaksiMasuk[$i] ?? 0;
            $dataKeluar[] = $transaksiKeluar[$i] ?? 0;
        }

        return view('dashboard', [
            'totalBarang' => Obat::count(),
            'barangMasuk' => Transaksi::where('jenis','masuk')->sum('jumlah'),
            'barangKeluar' => Transaksi::where('jenis','keluar')->sum('jumlah'),

            'kategori' => Kategori::count(),
            'jenis' => Jenis::count(),
            'satuan' => Satuan::count(),

            'kadaluarsa' => Obat::whereNotNull('tgl_kadaluarsa')->where('tgl_kadaluarsa','<=', now()->addDays(30))->get(),
            
            // For Chart
            'bulan' => $bulan,
            'dataMasuk' => $dataMasuk,
            'dataKeluar' => $dataKeluar,

            // For Data Stock Barang Table
            'stockBarang' => Obat::with(['jenis', 'satuan'])->orderBy('created_at', 'desc')->take(5)->get()
        ]);
    }
}
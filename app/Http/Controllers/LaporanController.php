<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class LaporanController extends Controller
{
    public function masuk(Request $request)
    {
        $query = Transaksi::with('obat')->whereHas('obat')->where('jenis', 'masuk');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $transaksi = $query->latest()->paginate(10)->withQueryString();
        return view('laporan.masuk', compact('transaksi'));
    }

    public function keluar(Request $request)
    {
        $query = Transaksi::with('obat')->whereHas('obat')->whereIn('jenis', ['keluar', 'kadaluarsa']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $transaksi = $query->latest()->paginate(10)->withQueryString();
        return view('laporan.keluar', compact('transaksi'));
    }

    public function exportMasuk(Request $request)
    {
        $query = Transaksi::with('obat')->whereHas('obat')->where('jenis', 'masuk');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $transaksi = $query->latest()->get();

        $filename = "laporan_barang_masuk_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($transaksi) {
            $file = fopen('php://output', 'w');
            
            // Excel UTF-8 compatibility BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Specify delimiter for Excel
            fwrite($file, "sep=,\n");

            // Header columns
            fputcsv($file, ['No', 'Kode Barang', 'Nama Barang', 'Tanggal Masuk', 'Satuan', 'Jumlah Masuk', 'Keterangan'], ',');

            foreach ($transaksi as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    $item->obat->kode ?? '-',
                    $item->obat->nama ?? 'Obat Dihapus',
                    $item->tanggal,
                    $item->obat->satuan->nama ?? '-',
                    $item->jumlah,
                    $item->keterangan
                ], ',');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportKeluar(Request $request)
    {
        $query = Transaksi::with('obat')->whereHas('obat')->whereIn('jenis', ['keluar', 'kadaluarsa']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $transaksi = $query->latest()->get();

        $filename = "laporan_barang_keluar_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($transaksi) {
            $file = fopen('php://output', 'w');
            
            // Excel UTF-8 compatibility BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Specify delimiter for Excel
            fwrite($file, "sep=,\n");

            // Header columns
            fputcsv($file, ['No', 'Kode Barang', 'Nama Barang', 'Tanggal Keluar', 'Satuan', 'Jumlah Keluar', 'Keterangan'], ',');

            foreach ($transaksi as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    $item->obat->kode ?? '-',
                    $item->obat->nama ?? 'Obat Dihapus',
                    $item->tanggal,
                    $item->obat->satuan->nama ?? '-',
                    $item->jumlah,
                    $item->keterangan
                ], ',');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

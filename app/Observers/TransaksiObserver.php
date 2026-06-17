<?php

namespace App\Observers;

use App\Models\Transaksi;
use App\Models\ObatBatch;

class TransaksiObserver
{
    /**
     * Triggered after a transaction is created, updated, or deleted.
     */
    public function saved(Transaksi $transaksi)
    {
        self::recalculateBatches($transaksi->obat_id);
    }

    public function deleted(Transaksi $transaksi)
    {
        self::recalculateBatches($transaksi->obat_id);
    }

    /**
     * Recalculates the batches for a specific obat based on its transaction history.
     */
    public static function recalculateBatches($obat_id)
    {
        // Hapus semua batch lama untuk obat ini
        ObatBatch::where('obat_id', $obat_id)->delete();

        // Ambil semua transaksi masuk (ordered by oldest first)
        $masuk = Transaksi::where('obat_id', $obat_id)
            ->where('jenis', 'masuk')
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // 1. Buat batch dari transaksi masuk
        foreach ($masuk as $m) {
            $tgl_kd = $m->tgl_kadaluarsa ?: '2099-12-31'; // Default if null

            $batch = ObatBatch::firstOrCreate(
                ['obat_id' => $obat_id, 'tgl_kadaluarsa' => $tgl_kd],
                ['stok' => 0]
            );

            $batch->stok += $m->jumlah;
            $batch->save();
        }

        // 2. Potong stok dari transaksi keluar/kadaluarsa menggunakan metode FEFO (First Expire First Out)
        $keluar = Transaksi::where('obat_id', $obat_id)
            ->whereIn('jenis', ['keluar', 'kadaluarsa'])
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        foreach ($keluar as $k) {
            $sisa_potong = $k->jumlah;

            // Ambil batch dengan stok > 0, urutkan dari tgl_kadaluarsa paling awal (FEFO)
            $batches = ObatBatch::where('obat_id', $obat_id)
                ->where('stok', '>', 0)
                ->orderBy('tgl_kadaluarsa', 'asc')
                ->get();

            foreach ($batches as $b) {
                if ($sisa_potong <= 0) break;

                if ($b->stok >= $sisa_potong) {
                    $b->stok -= $sisa_potong;
                    $b->save();
                    $sisa_potong = 0;
                } else {
                    $sisa_potong -= $b->stok;
                    $b->stok = 0;
                    $b->save();
                }
            }
        }

        // Hapus batch yang stoknya 0 untuk menghemat space (opsional, tapi disarankan)
        ObatBatch::where('obat_id', $obat_id)->where('stok', '<=', 0)->delete();
    }
}

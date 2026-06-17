<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Obat;

class TransaksiController extends Controller
{
    public function index($jenis)
    {
        $validJenis = ['masuk', 'keluar', 'kadaluarsa'];
        if (!in_array($jenis, $validJenis)) {
            abort(404);
        }

        if ($jenis === 'keluar') {
            $transaksi = Transaksi::with('obat')->has('obat')->whereIn('jenis', ['keluar', 'kadaluarsa'])->latest()->paginate(10);
        } else {
            $transaksi = Transaksi::with('obat')->has('obat')->where('jenis', $jenis)->latest()->paginate(10);
        }

        $obat = Obat::all();

        $view = 'transaksi.' . $jenis;
        if (!view()->exists($view)) {
            abort(404);
        }

        return view($view, compact('transaksi', 'obat', 'jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'obat_id'        => 'required|exists:obat,id',
            'jenis'          => 'required|in:masuk,keluar,kadaluarsa',
            'jumlah'         => 'required|numeric|min:1',
            'tanggal'        => 'required|date',
            'tgl_kadaluarsa' => 'nullable|date',
        ]);

        $obat = Obat::findOrFail($request->obat_id);

        // Validasi stok jika obat keluar / kadaluarsa
        if (in_array($request->jenis, ['keluar', 'kadaluarsa'])) {
            if ($request->jumlah > $obat->stok) {
                $msg = 'Stok obat tidak mencukupi! Stok saat ini: ' . $obat->stok;
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'errors' => ['jumlah' => [$msg]]], 422);
                }
                return back()->withInput()->withErrors(['jumlah' => $msg]);
            }
        }

        $transaksi = Transaksi::create([
            'obat_id'        => $request->obat_id,
            'jenis'          => $request->jenis,
            'jumlah'         => $request->jumlah,
            'tanggal'        => $request->tanggal,
            'keterangan'     => $request->keterangan,
            'tgl_kadaluarsa' => $request->jenis === 'masuk' ? $request->tgl_kadaluarsa : null,
        ]);

        // Cek stok menipis setelah transaksi keluar
        $stokMenipisMsg = null;
        if (in_array($request->jenis, ['keluar', 'kadaluarsa'])) {
            $obat->refresh();
            $stokMin = $obat->stok_min;
            if ($stokMin > 0 && $obat->stok <= $stokMin) {
                $stokMenipisMsg = '⚠️ Peringatan: Stok obat ' . $obat->nama . ' sudah menipis! Sisa stok: ' . $obat->stok . ' (Min: ' . $stokMin . ')';
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success'          => true,
                'message'          => 'Transaksi berhasil ditambahkan',
                'stok_menipis_msg' => $stokMenipisMsg,
                'data'             => $transaksi
            ]);
        }

        $successMsg = 'Transaksi berhasil ditambahkan';
        if ($stokMenipisMsg) {
            $successMsg .= ' | ' . $stokMenipisMsg;
        }
        return back()->with('success', $successMsg)->with('stok_menipis', $stokMenipisMsg);
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $request->validate([
            'obat_id'        => 'required|exists:obat,id',
            'jenis'          => 'required|in:masuk,keluar,kadaluarsa',
            'jumlah'         => 'required|numeric|min:1',
            'tanggal'        => 'required|date',
            'tgl_kadaluarsa' => 'nullable|date',
        ]);

        $obat_baru = Obat::findOrFail($request->obat_id);

        // Hitung stok sementara
        $temp_stok_baru = $obat_baru->stok;
        if ($transaksi->obat_id == $request->obat_id) {
            if ($transaksi->jenis == 'masuk') {
                $temp_stok_baru -= $transaksi->jumlah;
            } else {
                $temp_stok_baru += $transaksi->jumlah;
            }
        }

        if (in_array($request->jenis, ['keluar', 'kadaluarsa'])) {
            if ($request->jumlah > $temp_stok_baru) {
                $msg = 'Stok obat tidak mencukupi setelah perubahan! Stok tersedia: ' . $temp_stok_baru;
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'errors' => ['jumlah' => [$msg]]], 422);
                }
                return back()->withInput()->withErrors(['jumlah' => $msg]);
            }
        }

        // Update transaksi
        $transaksi->update([
            'obat_id'        => $request->obat_id,
            'jenis'          => $request->jenis,
            'jumlah'         => $request->jumlah,
            'tanggal'        => $request->tanggal,
            'keterangan'     => $request->keterangan,
            'tgl_kadaluarsa' => $request->jenis === 'masuk' ? $request->tgl_kadaluarsa : null,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diperbarui',
                'data'    => $transaksi
            ]);
        }

        return back()->with('success', 'Transaksi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus'
            ]);
        }

        return back()->with('success', 'Transaksi berhasil dihapus');
    }
}

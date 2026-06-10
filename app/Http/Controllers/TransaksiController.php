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

        // Jika jenisnya 'keluar', kita load transaksi 'keluar' dan 'kadaluarsa' agar terhubung!
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
            'obat_id' => 'required|exists:obat,id',
            'jenis' => 'required|in:masuk,keluar,kadaluarsa',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'tgl_kadaluarsa' => 'nullable|date',
        ]);

        $obat = Obat::findOrFail($request->obat_id);

        // Validasi stok jika barang keluar / kadaluarsa
        if (in_array($request->jenis, ['keluar', 'kadaluarsa'])) {
            if ($request->jumlah > $obat->stok) {
                $msg = 'Stok obat tidak mencukupi! Stok saat ini: ' . $obat->stok;
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['jumlah' => [$msg]]
                    ], 422);
                }
                return back()->withInput()->withErrors(['jumlah' => $msg]);
            }
        }

        $transaksi = Transaksi::create($request->all());

        // Update stok obat
        if ($request->jenis == 'masuk') {
            $obat->stok += $request->jumlah;
            if ($request->filled('tgl_kadaluarsa')) {
                $obat->tgl_kadaluarsa = $request->tgl_kadaluarsa;
            }
        } elseif ($request->jenis == 'keluar' || $request->jenis == 'kadaluarsa') {
            $obat->stok -= $request->jumlah;
        }
        $obat->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil ditambahkan',
                'data' => $transaksi
            ]);
        }

        return back()->with('success', 'Transaksi berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'jenis' => 'required|in:masuk,keluar,kadaluarsa',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'tgl_kadaluarsa' => 'nullable|date',
        ]);

        $obat_lama = Obat::findOrFail($transaksi->obat_id);
        $obat_baru = Obat::findOrFail($request->obat_id);

        // Temp revert old stock to check availability
        $temp_stok_baru = $obat_baru->stok;
        if ($transaksi->obat_id == $request->obat_id) {
            // Jika obatnya sama, kita hitung stok tentatif dengan mengembalikan transaksi lama
            if ($transaksi->jenis == 'masuk') {
                $temp_stok_baru -= $transaksi->jumlah;
            } else {
                $temp_stok_baru += $transaksi->jumlah;
            }
        }

        // Validasi stok baru jika jenis transaksi keluar/kadaluarsa
        if (in_array($request->jenis, ['keluar', 'kadaluarsa'])) {
            if ($request->jumlah > $temp_stok_baru) {
                $msg = 'Stok obat tidak mencukupi setelah perubahan! Stok tersedia: ' . $temp_stok_baru;
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['jumlah' => [$msg]]
                    ], 422);
                }
                return back()->withInput()->withErrors(['jumlah' => $msg]);
            }
        }

        // Revert old stock permanently
        if ($transaksi->jenis == 'masuk') {
            $obat_lama->stok -= $transaksi->jumlah;
        } elseif ($transaksi->jenis == 'keluar' || $transaksi->jenis == 'kadaluarsa') {
            $obat_lama->stok += $transaksi->jumlah;
        }
        $obat_lama->save();

        // Update transaction
        $transaksi->update($request->all());

        // Apply new stock (refresh model in case it was the same model)
        $obat_baru = Obat::findOrFail($request->obat_id);
        if ($request->jenis == 'masuk') {
            $obat_baru->stok += $request->jumlah;
            if ($request->filled('tgl_kadaluarsa')) {
                $obat_baru->tgl_kadaluarsa = $request->tgl_kadaluarsa;
            }
        } elseif ($request->jenis == 'keluar' || $request->jenis == 'kadaluarsa') {
            $obat_baru->stok -= $request->jumlah;
        }
        $obat_baru->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diperbarui',
                'data' => $transaksi
            ]);
        }

        return back()->with('success', 'Transaksi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        // Revert old stock
        $obat = Obat::find($transaksi->obat_id);
        if ($transaksi->jenis == 'masuk') {
            $obat->stok -= $transaksi->jumlah;
        } elseif ($transaksi->jenis == 'keluar' || $transaksi->jenis == 'kadaluarsa') {
            $obat->stok += $transaksi->jumlah;
        }
        $obat->save();

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

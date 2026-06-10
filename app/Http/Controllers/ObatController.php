<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $query = Obat::with('transaksi');

        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->kategori . '%');
            });
        }

        $obat = $query->with('transaksi')->paginate(10);
        
        $kategoriList = \App\Models\Kategori::all();
        $jenisList = \App\Models\Jenis::all();
        $satuanList = \App\Models\Satuan::all();

        return view('obat.data', compact('obat', 'kategoriList', 'jenisList', 'satuanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
        ]);
        
        $lastObat = Obat::orderBy('id', 'desc')->first();
        $nextId = $lastObat ? $lastObat->id + 1 : 1;
        $kode = 'OBT-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        
        $data = $request->except('stok');
        $data['kode'] = $kode;

        $obat = Obat::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data obat berhasil ditambahkan',
                'data' => $obat
            ]);
        }

        return redirect()->back()->with('success', 'Data obat berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $obat = Obat::findOrFail($id);

        $request->validate([
            'nama' => 'required',
        ]);

        $obat->update($request->except(['kode', 'stok']));

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data obat berhasil diubah',
                'data' => $obat
            ]);
        }

        return redirect()->back()->with('success', 'Data obat berhasil diubah');
    }

    public function destroy($id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data obat berhasil dihapus'
            ]);
        }

        return redirect()->back()->with('success', 'Data obat berhasil dihapus');
    }
}

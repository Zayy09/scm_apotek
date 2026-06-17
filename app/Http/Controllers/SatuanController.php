<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Satuan;

class SatuanController extends Controller
{
    public function index(Request $request)
    {
        $query = Satuan::query();
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }
        $satuan = $query->paginate(10);
        return view('obat.satuan', compact('satuan'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required']);
        $satuan = Satuan::create([
            'nama'     => $request->nama,
            'stok_min' => $request->stok_min ?? 0,
        ]);
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Satuan berhasil ditambahkan', 'data' => $satuan]);
        }
        return redirect()->back()->with('success', 'Satuan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $satuan = Satuan::findOrFail($id);
        $satuan->update([
            'nama'     => $request->nama,
            'stok_min' => $request->stok_min ?? 0,
        ]);
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Satuan berhasil diubah', 'data' => $satuan]);
        }
        return redirect()->back()->with('success', 'Satuan berhasil diubah');
    }

    public function destroy($id)
    {
        Satuan::findOrFail($id)->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Satuan berhasil dihapus']);
        }
        return redirect()->back()->with('success', 'Satuan berhasil dihapus');
    }
}

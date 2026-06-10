<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = Kategori::query();
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }
        $kategori = $query->paginate(10);
        return view('obat.kategori', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required']);
        $kategori = Kategori::create($request->all());
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Kategori berhasil ditambahkan', 'data' => $kategori]);
        }
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->update($request->all());
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Kategori berhasil diubah', 'data' => $kategori]);
        }
        return redirect()->back()->with('success', 'Kategori berhasil diubah');
    }

    public function destroy($id)
    {
        Kategori::findOrFail($id)->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Kategori berhasil dihapus']);
        }
        return redirect()->back()->with('success', 'Kategori berhasil dihapus');
    }
}

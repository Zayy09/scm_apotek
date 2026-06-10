<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Jenis;

class JenisController extends Controller
{
    public function index(Request $request)
    {
        $query = Jenis::query();
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }
        $jenis = $query->paginate(10);
        return view('obat.jenis', compact('jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required'
        ]);
        $jenis = Jenis::create($request->only('nama'));
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Jenis berhasil ditambahkan', 'data' => $jenis]);
        }
        return redirect()->back()->with('success', 'Jenis berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required'
        ]);
        $jenis = Jenis::findOrFail($id);
        $jenis->update($request->except('kode'));
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Jenis berhasil diubah', 'data' => $jenis]);
        }
        return redirect()->back()->with('success', 'Jenis berhasil diubah');
    }

    public function destroy($id)
    {
        Jenis::findOrFail($id)->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Jenis berhasil dihapus']);
        }
        return redirect()->back()->with('success', 'Jenis berhasil dihapus');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Obat;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::with('obat');
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }
        $supplier = $query->paginate(10);
        $obatList = Obat::orderBy('nama')->get();
        return view('supplier.index', compact('supplier', 'obatList'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required']);
        $supplier = Supplier::create($request->only(['nama', 'no_telepon', 'email', 'alamat']));

        // Assign obat yang dipilih ke supplier ini
        if ($request->filled('obat_ids')) {
            Obat::whereIn('id', $request->obat_ids)->update(['supplier_id' => $supplier->id]);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Supplier berhasil ditambahkan', 'data' => $supplier]);
        }
        return redirect()->back()->with('success', 'Supplier berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->only(['nama', 'no_telepon', 'email', 'alamat']));

        // Lepas semua obat lama dari supplier ini, lalu assign yang baru
        Obat::where('supplier_id', $supplier->id)->update(['supplier_id' => null]);
        if ($request->filled('obat_ids')) {
            Obat::whereIn('id', $request->obat_ids)->update(['supplier_id' => $supplier->id]);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Supplier berhasil diubah', 'data' => $supplier]);
        }
        return redirect()->back()->with('success', 'Supplier berhasil diubah');
    }

    public function destroy($id)
    {
        // Lepas relasi obat sebelum hapus supplier
        Obat::where('supplier_id', $id)->update(['supplier_id' => null]);
        Supplier::findOrFail($id)->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Supplier berhasil dihapus']);
        }
        return redirect()->back()->with('success', 'Supplier berhasil dihapus');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }
        $supplier = $query->paginate(10);
        return view('supplier.index', compact('supplier'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required']);
        $supplier = Supplier::create($request->all());
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Supplier berhasil ditambahkan', 'data' => $supplier]);
        }
        return redirect()->back()->with('success', 'Supplier berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->all());
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Supplier berhasil diubah', 'data' => $supplier]);
        }
        return redirect()->back()->with('success', 'Supplier berhasil diubah');
    }

    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Supplier berhasil dihapus']);
        }
        return redirect()->back()->with('success', 'Supplier berhasil dihapus');
    }
}

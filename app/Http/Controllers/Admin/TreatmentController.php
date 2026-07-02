<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index()
    {
        $treatments = Treatment::orderBy('name')->paginate(20);
        return view('admin.treatment.index', compact('treatments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:treatments,name',
            'bentuk_sediaan' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Treatment::create($request->all());

        return redirect()->route('admin.treatment.index')->with('success', 'Tindakan berhasil ditambahkan.');
    }

    public function update(Request $request, Treatment $treatment)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:treatments,name,' . $treatment->id,
            'bentuk_sediaan' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $treatment->update($request->all());

        return redirect()->route('admin.treatment.index')->with('success', 'Tindakan berhasil diperbarui.');
    }

    public function destroy(Treatment $treatment)
    {
        $treatment->delete();
        return redirect()->route('admin.treatment.index')->with('success', 'Tindakan berhasil dihapus.');
    }
}

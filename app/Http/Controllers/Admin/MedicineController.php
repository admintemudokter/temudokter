<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::orderBy('name')->paginate(20);
        return view('admin.medicine.index', compact('medicines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:medicines,name',
            'description' => 'nullable|string',
        ]);

        Medicine::create($request->all());

        return redirect()->route('admin.medicine.index')->with('success', 'Obat berhasil ditambahkan.');
    }

    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:medicines,name,' . $medicine->id,
            'description' => 'nullable|string',
        ]);

        $medicine->update($request->all());

        return redirect()->route('admin.medicine.index')->with('success', 'Obat berhasil diperbarui.');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('admin.medicine.index')->with('success', 'Obat berhasil dihapus.');
    }
}

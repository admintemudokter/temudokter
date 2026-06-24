<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::withCount(['consultations', 'activeConsultations'])->orderBy('name')->get();
        return view('admin.doctor.index', compact('doctors'));
    }

    public function create()
    {
        return view('admin.doctor.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:150',
            'email'             => 'required|email|unique:doctors,email',
            'password'          => 'required|string|min:8|confirmed',
            'specialization'    => 'required|string|max:150',
            'experience_years'  => 'nullable|integer|min:0',
            'practice_location' => 'nullable|string|max:255',
            'education'         => 'nullable|string|max:255',
            'str_number'        => 'nullable|string|max:100',
            'sip_number'        => 'required|string|max:100',
            'phone'             => 'nullable|string|max:20',
            'bio'               => 'nullable|string|max:500',
            'photo'             => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('doctors', 'public');
        }

        Doctor::create([
            ...$data,
            'password' => Hash::make($data['password']),
            'status'   => 'offline',
        ]);

        return redirect()->route('admin.doctor.index')
            ->with('success', "Dokter {$data['name']} berhasil ditambahkan.");
    }

    public function edit(Doctor $doctor)
    {
        return view('admin.doctor.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:150',
            'email'             => 'required|email|unique:doctors,email,' . $doctor->id,
            'password'          => 'nullable|string|min:8|confirmed',
            'specialization'    => 'required|string|max:150',
            'experience_years'  => 'nullable|integer|min:0',
            'practice_location' => 'nullable|string|max:255',
            'education'         => 'nullable|string|max:255',
            'str_number'        => 'nullable|string|max:100',
            'sip_number'        => 'required|string|max:100',
            'phone'             => 'nullable|string|max:20',
            'bio'               => 'nullable|string|max:500',
            'status'            => 'required|in:online,offline,inactive',
            'photo'             => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('doctors', 'public');
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $doctor->update($data);

        return redirect()->route('admin.doctor.index')
            ->with('success', "Data dokter {$doctor->name} berhasil diperbarui.");
    }

    public function destroy(Doctor $doctor)
    {
        try {
            $name = $doctor->name;
            $doctor->delete();
            return redirect()->route('admin.doctor.index')->with('success', "Dokter {$name} berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->route('admin.doctor.index')->with('error', "Gagal menghapus dokter. Dokter mungkin memiliki riwayat konsultasi yang tidak bisa dihapus.");
        }
    }
}

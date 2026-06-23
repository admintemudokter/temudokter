<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomecareBlock;
use Illuminate\Http\Request;

class HomecareScheduleController extends Controller
{
    public function index()
    {
        $blocks = HomecareBlock::orderBy('date', 'desc')->orderBy('time')->get();
        return view('admin.homecare.schedule', compact('blocks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:block,open',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'reason' => 'nullable|string|max:255',
        ]);

        HomecareBlock::create($validated);

        if ($validated['type'] === 'open') {
            $message = 'Jadwal hari biasa berhasil dibuka.';
        } else {
            $message = 'Jadwal Homecare berhasil diblokir.';
        }

        return redirect()->route('admin.homecare.schedule.index')->with('success', $message);
    }

    public function destroy(HomecareBlock $block)
    {
        $block->delete();
        return redirect()->route('admin.homecare.schedule.index')
            ->with('success', 'Blokir jadwal berhasil dihapus. Pasien kini bisa memesan kembali.');
    }
}

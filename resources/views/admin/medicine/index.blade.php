@extends('layouts.admin')
@section('title', 'Master Data Obat')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Master Data Obat</h1>
        <p class="text-slate-500 text-sm mt-1">Kelola daftar obat yang dapat diresepkan oleh dokter.</p>
    </div>
    <button onclick="document.getElementById('modal-add').showModal()" class="btn-primary">
        + Tambah Obat
    </button>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-semibold">
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Nama Obat</th>
                    <th class="px-6 py-4">Bentuk Sediaan</th>
                    <th class="px-6 py-4">Deskripsi/Keterangan</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($medicines as $med)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 text-slate-500">#{{ $med->id }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $med->name }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $med->bentuk_sediaan ?: '-' }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $med->description ?: '-' }}</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button onclick="editMed({{ $med->id }}, '{{ addslashes($med->name) }}', '{{ addslashes($med->bentuk_sediaan) }}', '{{ addslashes($med->description) }}')" class="text-brand-600 hover:text-brand-800 font-medium">Edit</button>
                        <form action="{{ route('admin.medicine.destroy', $med->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus obat ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                        Belum ada data obat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($medicines->hasPages())
    <div class="p-4 border-t border-slate-100">
        {{ $medicines->links() }}
    </div>
    @endif
</div>

{{-- Modal Add --}}
<dialog id="modal-add" class="modal bg-transparent p-0 backdrop:bg-slate-900/50 open:animate-in open:fade-in">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden m-auto mt-[10vh]">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-lg text-slate-800">Tambah Obat Baru</h3>
            <form method="dialog">
                <button class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </form>
        </div>
        <form action="{{ route('admin.medicine.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="form-label">Nama Obat <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" class="form-input" required placeholder="Contoh: Paracetamol 500mg">
                </div>
                <div>
                    <label class="form-label">Bentuk Sediaan</label>
                    <select name="bentuk_sediaan" class="form-select">
                        <option value="">-- Pilih Bentuk Sediaan --</option>
                        <option value="Tablet">Tablet</option>
                        <option value="Kapsul">Kapsul</option>
                        <option value="Cair(sirup)">Cair(sirup)</option>
                        <option value="Sirup(kering)">Sirup(kering)</option>
                        <option value="Suspensi">Suspensi</option>
                        <option value="Tetes">Tetes</option>
                        <option value="Krim">Krim</option>
                        <option value="Salep">Salep</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Deskripsi / Keterangan</label>
                    <textarea name="description" class="form-textarea" rows="3" placeholder="Contoh: Obat penurun panas..."></textarea>
                </div>
            </div>
            <div class="p-6 bg-slate-50 flex justify-end gap-3 rounded-b-2xl">
                <form method="dialog" class="inline">
                    <button type="button" onclick="document.getElementById('modal-add').close()" class="btn-ghost">Batal</button>
                </form>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</dialog>

{{-- Modal Edit --}}
<dialog id="modal-edit" class="modal bg-transparent p-0 backdrop:bg-slate-900/50 open:animate-in open:fade-in">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden m-auto mt-[10vh]">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-lg text-slate-800">Edit Obat</h3>
            <form method="dialog">
                <button class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </form>
        </div>
        <form id="form-edit" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="form-label">Nama Obat <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="edit-name" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Bentuk Sediaan</label>
                    <select name="bentuk_sediaan" id="edit-bentuk" class="form-select">
                        <option value="">-- Pilih Bentuk Sediaan --</option>
                        <option value="Tablet">Tablet</option>
                        <option value="Kapsul">Kapsul</option>
                        <option value="Cair(sirup)">Cair(sirup)</option>
                        <option value="Sirup(kering)">Sirup(kering)</option>
                        <option value="Suspensi">Suspensi</option>
                        <option value="Tetes">Tetes</option>
                        <option value="Krim">Krim</option>
                        <option value="Salep">Salep</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Deskripsi / Keterangan</label>
                    <textarea name="description" id="edit-description" class="form-textarea" rows="3"></textarea>
                </div>
            </div>
            <div class="p-6 bg-slate-50 flex justify-end gap-3 rounded-b-2xl">
                <form method="dialog" class="inline">
                    <button type="button" onclick="document.getElementById('modal-edit').close()" class="btn-ghost">Batal</button>
                </form>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</dialog>

<script>
    function editMed(id, name, bentuk, desc) {
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-bentuk').value = bentuk;
        document.getElementById('edit-description').value = desc;
        document.getElementById('form-edit').action = '/admin/medicine/' + id;
        document.getElementById('modal-edit').showModal();
    }
</script>
@endsection

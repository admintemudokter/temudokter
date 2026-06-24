@extends('layouts.app')

@section('title', 'Daftar Homecare')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-brand-950 via-brand-900 to-emerald-900 flex flex-col" x-data="homecareForm()">

    {{-- Top bar --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-white/10">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <div class="w-7 h-7 bg-white/20 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
             <span class="font-heading font-bold text-xl text-white">Temu <span class="text-emerald-400">Dokter</span></span>
        </a>
        <div class="text-white/60 text-sm">Formulir Homecare</div>
    </div>

    <div class="flex-1 flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-2xl">

            {{-- Progress Steps --}}
            <div class="flex items-center justify-center gap-0 mb-10">
                @foreach([['num'=>1,'label'=>'Data Diri'],['num'=>2,'label'=>'Keluhan'],['num'=>3,'label'=>'Konfirmasi']] as $s)
                <div class="flex items-center">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300"
                             :class="step >= {{ $s['num'] }} ? 'bg-white text-brand-700 shadow-lg' : 'bg-white/20 text-white/60'">
                            <span x-show="step > {{ $s['num'] }}">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </span>
                            <span x-show="step <= {{ $s['num'] }}">{{ $s['num'] }}</span>
                        </div>
                        <span class="text-xs mt-1.5 font-medium transition-colors duration-300"
                              :class="step >= {{ $s['num'] }} ? 'text-white' : 'text-white/40'">{{ $s['label'] }}</span>
                    </div>
                    @if(!$loop->last)
                    <div class="w-20 sm:w-32 h-0.5 mx-2 mb-5 transition-all duration-300"
                         :class="step > {{ $s['num'] }} ? 'bg-white' : 'bg-white/20'"></div>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- Card --}}
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <form action="{{ route('patient.homecare.store') }}" method="POST" enctype="multipart/form-data" id="homecare-form">
                    @csrf

                    @if($errors->any())
                        <div class="m-8 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-600">
                            <p class="font-bold mb-2">Terdapat kesalahan pada pengisian form:</p>
                            <ul class="list-disc pl-5 text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- ===== STEP 1: Personal Info ===== --}}
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                        <div class="px-8 pt-8 pb-6 border-b border-slate-100">
                            <h2 class="font-heading text-xl font-bold text-slate-800 mb-1">Data Diri Pasien</h2>
                            <p class="text-slate-500 text-sm">Isi informasi berikut dengan benar dan lengkap.</p>
                        </div>
                        <div class="px-8 py-6 space-y-5">
                            {{-- Full Name --}}
                            <div>
                                <label class="form-label" for="full_name">Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input type="text" id="full_name" name="full_name"
                                       class="form-input @error('full_name') border-rose-400 @enderror"
                                       placeholder="Nama sesuai KTP"
                                       value="{{ old('full_name') }}"
                                       x-model="form.full_name" required>
                                @error('full_name') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            {{-- WhatsApp & Email --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label" for="whatsapp_number">Nomor WhatsApp <span class="text-rose-500">*</span></label>
                                    <div class="flex gap-2">
                                        <span class="flex items-center px-3 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-600 font-medium">+62</span>
                                        <input type="tel" id="whatsapp_number" name="whatsapp_number"
                                               class="form-input flex-1 @error('whatsapp_number') border-rose-400 @enderror"
                                               placeholder="81234567890"
                                               value="{{ old('whatsapp_number') }}"
                                               x-model="form.whatsapp_number" required>
                                    </div>
                                    @error('whatsapp_number') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="form-label" for="email">Alamat Email <span class="text-slate-400 font-normal text-xs ml-1">(Opsional)</span></label>
                                    <input type="email" id="email" name="email"
                                           class="form-input @error('email') border-rose-400 @enderror"
                                           placeholder="nama@email.com"
                                           value="{{ old('email') }}"
                                           x-model="form.email">
                                    <p class="text-xs text-slate-500 mt-1">Digunakan untuk mengirim riwayat konsultasi.</p>
                                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Age & Gender --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="form-label" for="age">Usia <span class="text-rose-500">*</span></label>
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" id="age" name="age"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 2);"
                                           class="form-input @error('age') border-rose-400 @enderror"
                                           placeholder="25"
                                           value="{{ old('age') }}"
                                           x-model="form.age" required>
                                    @error('age') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="form-label" for="gender">Jenis Kelamin <span class="text-rose-500">*</span></label>
                                    <select id="gender" name="gender"
                                            class="form-select @error('gender') border-rose-400 @enderror"
                                            x-model="form.gender" required>
                                        <option value="">Pilih...</option>
                                        <option value="laki-laki" {{ old('gender')==='laki-laki'?'selected':'' }}>Laki-laki</option>
                                        <option value="perempuan" {{ old('gender')==='perempuan'?'selected':'' }}>Perempuan</option>
                                    </select>
                                    @error('gender') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="form-label" for="occupation">Pekerjaan <span class="text-rose-500" x-show="!form.no_occupation">*</span></label>
                                    <input type="text" name="occupation" id="occupation" value="{{ old('occupation') }}" placeholder="Contoh: Pegawai Swasta"
                                           class="form-input" x-model="form.occupation" x-bind:readonly="form.no_occupation" :required="!form.no_occupation"
                                           :class="form.no_occupation ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : ''">
                                    <div class="mt-2 flex items-center gap-2">
                                        <input type="checkbox" id="no_occupation" class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500" 
                                               x-model="form.no_occupation" @change="if(form.no_occupation) form.occupation = '-'; else if(form.occupation === '-') form.occupation = '';">
                                        <label for="no_occupation" class="text-sm text-slate-600 cursor-pointer">Tidak memiliki pekerjaan / Mengurus Rumah Tangga</label>
                                    </div>
                                    @error('occupation') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Address Section (Indonesia) --}}
                            <div class="col-span-1 sm:col-span-2 space-y-4 pt-2">
                                <h3 class="text-sm font-bold text-slate-800 border-b pb-2">Alamat Domisili Lengkap</h3>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    {{-- Province --}}
                                    <div>
                                        <label class="form-label" for="province">Provinsi <span class="text-rose-500">*</span></label>
                                        <select id="province" class="form-select bg-slate-50 cursor-not-allowed" disabled required>
                                            <option value="Jawa Barat" selected>Jawa Barat</option>
                                        </select>
                                        <input type="hidden" name="province" value="Jawa Barat">
                                    </div>
                                    {{-- City --}}
                                    <div>
                                        <label class="form-label" for="city">Kabupaten/Kota <span class="text-rose-500">*</span></label>
                                        <select id="city" class="form-select bg-slate-50 cursor-not-allowed" disabled required>
                                            <option value="Kota Bekasi" selected>Kota Bekasi</option>
                                        </select>
                                        <input type="hidden" name="city" value="Kota Bekasi">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    {{-- District --}}
                                    <div>
                                        <label class="form-label" for="district">Kecamatan <span class="text-rose-500">*</span></label>
                                        <select id="district" name="district" class="form-select @error('district') border-rose-400 @enderror" x-model="selectedDistrict" @change="onDistrictChange()" required>
                                            <option value="">Pilih Kecamatan...</option>
                                            <template x-for="d in districts" :key="d.name">
                                                <option :value="d.name" x-text="d.name" :selected="d.name === selectedDistrict"></option>
                                            </template>
                                        </select>
                                        @error('district') <p class="form-error">{{ $message }}</p> @enderror
                                    </div>
                                    {{-- Village --}}
                                    <div>
                                        <label class="form-label" for="village">Kelurahan/Desa <span class="text-rose-500">*</span></label>
                                        <select id="village" name="village" class="form-select @error('village') border-rose-400 @enderror" x-model="selectedVillage" @change="onVillageChange()" :disabled="!selectedDistrict" required>
                                            <option value="">Pilih Kelurahan/Desa...</option>
                                            <template x-for="v in availableVillages" :key="v">
                                                <option :value="v" x-text="v" :selected="v === selectedVillage"></option>
                                            </template>
                                        </select>
                                        @error('village') <p class="form-error">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Address --}}
                            <div>
                                <label class="form-label" for="address">Alamat Lengkap <span class="text-rose-500">*</span></label>
                                <textarea id="address" name="address" rows="3" class="form-textarea" placeholder="Alamat detail untuk kunjungan medis..." x-model="form.address" required></textarea>
                                @error('address') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="px-8 pb-8 flex justify-end">
                            <button type="button" @click="nextStep()"
                                    class="btn-primary">
                                Lanjutkan
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- ===== STEP 2: Complaint ===== --}}
                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                        <div class="px-8 pt-8 pb-6 border-b border-slate-100">
                            <h2 class="font-heading text-xl font-bold text-slate-800 mb-1">Keluhan Medis</h2>
                            <p class="text-slate-500 text-sm">Ceritakan keluhan Anda secara detail agar dokter dapat membantu lebih baik.</p>
                        </div>
                        <div class="px-8 py-6 space-y-5">
                            {{-- Complaint --}}
                            <div>
                                <label class="form-label" for="complaint_description">Deskripsi Keluhan <span class="text-rose-500">*</span></label>
                                <textarea id="complaint_description" name="complaint_description"
                                          rows="5"
                                          class="form-textarea @error('complaint_description') border-rose-400 @enderror"
                                          placeholder="Ceritakan keluhan Anda: sejak kapan, gejala apa saja, sudah minum obat apa, dll..."
                                          x-model="form.complaint_description" required>{{ old('complaint_description') }}</textarea>
                                <p class="form-hint">Minimal 20 karakter. Semakin detail semakin baik.</p>
                                @error('complaint_description') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            {{-- Allergies --}}
                            <div>
                                <label class="form-label" for="drug_allergies">Apakah ada alergi obat? <span class="text-rose-500">*</span></label>
                                <textarea id="drug_allergies" name="drug_allergies"
                                          rows="2"
                                          class="form-textarea @error('drug_allergies') border-rose-400 @enderror"
                                          placeholder="Sebutkan obat yang menyebabkan alergi jika ada..."
                                          x-model="form.drug_allergies"
                                          x-bind:readonly="form.no_allergies"
                                          :class="form.no_allergies ? 'bg-slate-100 text-slate-400 cursor-not-allowed focus:ring-0 focus:border-slate-300' : ''" required></textarea>
                                <div class="mt-2 flex items-center">
                                    <input type="checkbox" id="no_allergies" class="form-checkbox h-4 w-4 text-brand-600 border-slate-300 rounded focus:ring-brand-500" 
                                           x-model="form.no_allergies" 
                                           @change="if(form.no_allergies) form.drug_allergies = 'Tidak ada alergi obat'; else form.drug_allergies = '';">
                                    <label for="no_allergies" class="ml-2 text-sm text-slate-600 cursor-pointer">Tidak ada alergi obat</label>
                                </div>
                                @error('drug_allergies') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            {{-- Medical Image --}}
                            <div>
                                <label class="form-label">Foto Kondisi Medis <span class="text-slate-400 font-normal">(Opsional)</span></label>
                                <div class="upload-zone" @click="$refs.imageInput.click()">
                                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-slate-500 text-sm font-medium" x-text="imageFileName || 'Klik untuk upload foto'"></p>
                                    <p class="text-slate-400 text-xs mt-1">JPG, PNG — Maks. 2MB</p>
                                </div>
                                <input type="file" x-ref="imageInput" name="medical_image" class="hidden"
                                       accept=".jpg,.jpeg,.png"
                                       @change="
                                            let file = $event.target.files[0];
                                            if (file && file.size > 2 * 1024 * 1024) {
                                                alert('Ukuran file maksimal 2MB!');
                                                $event.target.value = '';
                                                imageFileName = null;
                                            } else {
                                                imageFileName = file?.name ?? null;
                                            }
                                       ">
                                @error('medical_image') <p class="form-error">{{ $message === 'validation.uploaded' ? 'File gagal diunggah, pastikan ukuran tidak lebih dari 2MB.' : $message }}</p> @enderror
                            </div>

                            {{-- Medical Document --}}
                            <div>
                                <label class="form-label">Dokumen Medis <span class="text-slate-400 font-normal">(Opsional)</span></label>
                                <div class="upload-zone" @click="$refs.docInput.click()">
                                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-slate-500 text-sm font-medium" x-text="docFileName || 'Klik untuk upload dokumen'"></p>
                                    <p class="text-slate-400 text-xs mt-1">PDF, JPG, PNG — Maks. 2MB</p>
                                </div>
                                <input type="file" x-ref="docInput" name="medical_document" class="hidden"
                                       accept=".jpg,.jpeg,.png,.pdf"
                                       @change="
                                            let file = $event.target.files[0];
                                            if (file && file.size > 2 * 1024 * 1024) {
                                                alert('Ukuran dokumen maksimal 2MB!');
                                                $event.target.value = '';
                                                docFileName = null;
                                            } else {
                                                docFileName = file?.name ?? null;
                                            }
                                       ">
                                @error('medical_document') <p class="form-error">{{ $message === 'validation.uploaded' ? 'Dokumen gagal diunggah, pastikan ukuran tidak lebih dari 2MB.' : $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="px-8 pb-8 flex items-center justify-between">
                            <button type="button" @click="step = 1" class="btn-ghost">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                                </svg>
                                Kembali
                            </button>
                            <button type="button" @click="nextStep()" class="btn-primary">
                                Lanjutkan
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- ===== STEP 3: Confirmation ===== --}}
                    <div x-show="step === 3" x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                        <div class="px-8 pt-8 pb-6 border-b border-slate-100">
                            <h2 class="font-heading text-xl font-bold text-slate-800 mb-1">Konfirmasi Data</h2>
                            <p class="text-slate-500 text-sm">Periksa kembali data Anda sebelum melanjutkan.</p>
                        </div>
                        <div class="px-8 py-6">
                            <div class="bg-slate-50 rounded-2xl p-5 space-y-4 mb-6">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-slate-500 text-xs mb-1">Nama Lengkap</p>
                                        <p class="font-semibold text-slate-800" x-text="form.full_name || '—'"></p>
                                    </div>
                                    <div>
                                        <p class="text-slate-500 text-xs mb-1">Nomor WhatsApp</p>
                                        <p class="font-semibold text-slate-800" x-text="'+62 ' + (form.whatsapp_number || '—')"></p>
                                    </div>
                                    <div>
                                        <p class="text-slate-500 text-xs mb-1">Usia</p>
                                        <p class="font-semibold text-slate-800" x-text="(form.age || '—') + ' tahun'"></p>
                                    </div>
                                    <div>
                                        <p class="text-slate-500 text-xs mb-1">Jenis Kelamin</p>
                                        <p class="font-semibold text-slate-800 capitalize" x-text="form.gender || '—'"></p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-slate-500 text-xs mb-1">Wilayah Bekasi</p>
                                        <p class="font-semibold text-slate-800" x-text="form.bekasi_area || '—'"></p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-slate-500 text-xs mb-1">Alamat Lengkap</p>
                                        <p class="font-semibold text-slate-800" x-text="form.address || '—'"></p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-slate-500 text-xs mb-1">Deskripsi Keluhan</p>
                                        <p class="text-slate-700 text-sm leading-relaxed" x-text="form.complaint_description || '—'"></p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-slate-500 text-xs mb-1">Alergi Obat</p>
                                        <p class="font-semibold text-slate-800" x-text="form.drug_allergies || '—'"></p>
                                    </div>
                                </div>
                            </div>

                            {{-- Informed Consent Button --}}
                            <div class="mb-6">
                                <button type="button" @click="showConsentModal = true" class="w-full py-4 px-6 border-2 border-brand-200 border-dashed rounded-xl flex items-center justify-between hover:border-brand-400 hover:bg-brand-50 transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 group-hover:bg-brand-600 group-hover:text-white transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-bold text-slate-800 text-sm">Lembar Informasi dan Persetujuan Tindakan <span class="text-rose-500">*</span></p>
                                            <p class="text-xs text-slate-500 mt-0.5" x-text="agreedToConsent ? 'Telah disetujui' : 'Klik untuk membaca dan menyetujui'"></p>
                                        </div>
                                    </div>
                                    <div x-show="agreedToConsent" class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <div x-show="!agreedToConsent" class="text-brand-600">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <div class="px-8 pb-8 flex items-center justify-between">
                            <button type="button" @click="step = 2" class="btn-ghost">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                                </svg>
                                Kembali
                            </button>
                            <button type="submit" class="btn-primary btn-lg" :disabled="!agreedToConsent || submitting" :class="(!agreedToConsent || submitting) ? 'opacity-50 cursor-not-allowed' : ''">
                                <svg x-show="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="submitting ? 'Memproses...' : 'Submit Pendaftaran'"></span>
                            </button>
                        </div>
                    </div>

                    {{-- ===== INFORMED CONSENT MODAL ===== --}}
                    <div x-show="showConsentModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" style="display: none;">
                        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showConsentModal = false"
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
                        
                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col relative z-10"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                            
                            {{-- Header --}}
                            <div class="flex items-center justify-between p-5 sm:px-8 border-b border-slate-100">
                                <h3 class="font-heading font-bold text-lg text-slate-800">LEMBAR INFORMASI DAN PERSETUJUAN TINDAKAN</h3>
                                <button type="button" @click="showConsentModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            {{-- Body --}}
                            <div class="p-5 sm:px-8 overflow-y-auto scrollbar-thin flex-1 text-sm text-slate-700 space-y-4">
                                <p class="font-bold text-center text-slate-800 uppercase mb-4">(INFORMED CONSENT LAYANAN HOME CARE TEMU DOKTER)</p>
                                
                                <p class="font-bold text-slate-800 uppercase">A. Tujuan</p>
                                <p>Layanan Home Care Temu Dokter merupakan pelayanan kesehatan yang dilakukan secara langsung oleh dokter dan/atau tenaga kesehatan di lokasi pasien (rumah, kantor, atau lokasi lain yang disepakati). Layanan ini bertujuan untuk memberikan pemeriksaan kesehatan, konsultasi medis, tindakan medis sesuai kewenangan, pemberian terapi, edukasi kesehatan, pemantauan kondisi pasien, serta rekomendasi pemeriksaan atau rujukan lanjutan apabila diperlukan.</p>
                                
                                <p class="font-bold text-slate-800 uppercase mt-4">B. Keikutsertaan</p>
                                <p>Keikutsertaan Anda dalam layanan Home Care bersifat sukarela dan atas kehendak pribadi tanpa paksaan dari pihak mana pun.</p>
                                <p>Anda berhak untuk menolak, menghentikan, atau tidak melanjutkan pelayanan apabila tidak menyetujui syarat dan ketentuan yang berlaku.</p>
                                <p>Dengan menggunakan layanan Home Care Temu Dokter, Anda dianggap telah membaca, memahami, dan menyetujui seluruh ketentuan yang tercantum dalam dokumen ini.</p>
                                
                                <p class="font-bold text-slate-800 uppercase mt-4">C. Durasi dan Prosedur</p>
                                <p>Pelayanan Home Care dilakukan sesuai jadwal yang telah disepakati antara pasien dan penyelenggara layanan.</p>
                                <p>Selama kunjungan, pasien dapat menyampaikan keluhan, riwayat penyakit, riwayat alergi, riwayat pengobatan, hasil pemeriksaan penunjang, maupun informasi kesehatan lain yang relevan.</p>
                                <p>Dokter akan melakukan anamnesis, pemeriksaan fisik, dan apabila diperlukan dapat melakukan tindakan medis sesuai kompetensi dan kewenangan profesi serta berdasarkan persetujuan pasien.</p>
                                <p class="mt-2">Berdasarkan hasil pemeriksaan, dokter dapat memberikan:</p>
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Edukasi dan konsultasi kesehatan.</li>
                                    <li>Resep obat sesuai indikasi medis.</li>
                                    <li>Tindakan medis yang sesuai kewenangan.</li>
                                    <li>Surat keterangan medis sesuai ketentuan yang berlaku.</li>
                                    <li>Anjuran pemeriksaan penunjang.</li>
                                    <li>Rekomendasi rujukan ke fasilitas pelayanan kesehatan apabila diperlukan.</li>
                                </ul>
                                <p class="mt-2">Pasien memahami bahwa kualitas pelayanan dan keputusan medis sangat bergantung pada kelengkapan, keakuratan, dan kejujuran informasi yang diberikan kepada dokter.</p>

                                <p class="font-bold text-slate-800 uppercase mt-4">D. Manfaat</p>
                                <p>Layanan Home Care Temu Dokter dapat memberikan manfaat berupa:</p>
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Pemeriksaan kesehatan secara langsung di lokasi pasien.</li>
                                    <li>Konsultasi dan edukasi kesehatan.</li>
                                    <li>Penegakan diagnosis berdasarkan anamnesis dan pemeriksaan fisik.</li>
                                    <li>Pemberian terapi dan tindakan medis sesuai indikasi.</li>
                                    <li>Pemantauan kondisi kesehatan pasien.</li>
                                    <li>Rekomendasi pemeriksaan lanjutan atau rujukan apabila diperlukan.</li>
                                </ul>

                                <p class="font-bold text-slate-800 uppercase mt-4">E. Risiko dan Ketidaknyamanan</p>
                                <p>Pasien memahami dan menyetujui bahwa:</p>
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Setiap tindakan medis memiliki manfaat dan risiko yang telah dijelaskan oleh dokter sesuai kondisi pasien.</li>
                                    <li>Risiko alergi, efek samping obat, interaksi obat, perdarahan, infeksi, nyeri, memar, atau komplikasi lain dapat terjadi meskipun tindakan telah dilakukan sesuai standar profesi.</li>
                                    <li>Pasien wajib menyampaikan secara lengkap dan jujur mengenai riwayat penyakit, alergi, kehamilan, penggunaan obat, serta informasi medis lain yang relevan.</li>
                                    <li>Dokter tidak bertanggung jawab atas akibat yang timbul akibat informasi yang tidak lengkap, tidak akurat, atau tidak disampaikan oleh pasien.</li>
                                    <li>Dalam keadaan darurat atau kondisi yang memerlukan penanganan lebih lanjut, dokter dapat merekomendasikan pasien untuk segera dirujuk ke fasilitas pelayanan kesehatan yang lebih memadai.</li>
                                    <li>Kondisi lingkungan tempat pelayanan yang tidak mendukung dapat membatasi pelaksanaan tindakan medis tertentu demi keselamatan pasien dan tenaga kesehatan.</li>
                                </ul>

                                <p class="font-bold text-slate-800 uppercase mt-4">F. Kerahasiaan Data</p>
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Data pribadi dan informasi kesehatan pasien akan dijaga kerahasiaannya sesuai ketentuan peraturan perundang-undangan yang berlaku.</li>
                                    <li>Data dapat disimpan dan dikelola oleh penyelenggara layanan untuk keperluan pelayanan kesehatan, rekam medis, peningkatan mutu layanan, pemenuhan kewajiban hukum, serta kepentingan lain yang diperbolehkan oleh peraturan perundang-undangan.</li>
                                    <li>Pasien memahami bahwa data dapat diakses oleh tenaga kesehatan, fasilitas pelayanan kesehatan, perusahaan asuransi (apabila relevan), atau pihak lain yang berwenang sesuai ketentuan hukum yang berlaku.</li>
                                </ul>

                                <p class="font-bold text-slate-800 uppercase mt-4">G. Pembiayaan</p>
                                <p>Biaya yang timbul dari penggunaan layanan Home Care menjadi tanggung jawab pasien sesuai tarif yang berlaku.</p>
                                <p>Biaya pelayanan yang telah diberikan tidak dapat diminta kembali kecuali ditentukan lain oleh kebijakan penyelenggara layanan.</p>

                                <p class="font-bold text-slate-800 uppercase mt-4">H. Klarifikasi</p>
                                <p>Apabila Anda memiliki pertanyaan, memerlukan penjelasan lebih lanjut, atau membutuhkan klarifikasi terkait layanan Home Care Temu Dokter, Anda dapat menghubungi:<br>Email: admintemudokter@gmail.com<br>atau melalui kontak resmi yang tersedia pada website dan aplikasi Temu Dokter.</p>

                                <p class="font-bold text-slate-800 uppercase mt-4">I. Pernyataan Persetujuan</p>
                                <p>Dengan menggunakan layanan Home Care Temu Dokter, saya menyatakan bahwa:</p>
                                <ul class="list-disc pl-5 space-y-1 mb-4">
                                    <li>Saya telah membaca, memahami, dan menyetujui seluruh isi Lembar Informasi dan Persetujuan (Informed Consent) ini.</li>
                                    <li>Saya memberikan informasi kesehatan secara sadar, lengkap, dan sebenar-benarnya.</li>
                                    <li>Saya memahami manfaat, risiko, dan kemungkinan komplikasi yang dapat timbul dari pemeriksaan maupun tindakan medis yang dilakukan.</li>
                                    <li>Saya memberikan persetujuan kepada dokter dan/atau tenaga kesehatan yang bertugas untuk melakukan pemeriksaan dan tindakan medis yang diperlukan sesuai indikasi medis.</li>
                                    <li>Saya memahami bahwa dokter dapat menyarankan pemeriksaan lanjutan atau rujukan ke fasilitas pelayanan kesehatan apabila kondisi saya memerlukan evaluasi atau penanganan lebih lanjut.</li>
                                    <li>Saya membebaskan dokter dan penyelenggara layanan dari tuntutan yang timbul akibat informasi yang tidak lengkap, tidak akurat, atau tidak benar yang saya berikan selama pelayanan, sepanjang dokter telah menjalankan pelayanan sesuai standar profesi dan ketentuan yang berlaku.</li>
                                </ul>

                                <div class="bg-brand-50 p-4 rounded-xl border border-brand-100 space-y-3 mt-4">
                                    <label class="flex items-start gap-3 cursor-pointer group">
                                        <input type="checkbox" x-model="consent1" class="form-checkbox mt-0.5 w-5 h-5 rounded text-brand-600 border-brand-300 focus:ring-brand-500">
                                        <span class="text-sm font-medium text-slate-700">Saya menyetujui Informed Consent, Syarat dan Ketentuan, serta Kebijakan Privasi yang berlaku.</span>
                                    </label>
                                    <label class="flex items-start gap-3 cursor-pointer group">
                                        <input type="checkbox" x-model="consent2" class="form-checkbox mt-0.5 w-5 h-5 rounded text-brand-600 border-brand-300 focus:ring-brand-500">
                                        <span class="text-sm font-medium text-slate-700">Saya bersedia menerima informasi layanan, edukasi kesehatan, promosi, dan komunikasi lain dari penyelenggara layanan serta pihak yang bekerja sama sesuai ketentuan yang berlaku.</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Footer --}}
                            <div class="p-5 sm:px-8 border-t border-slate-100 bg-slate-50 flex justify-end gap-3 rounded-b-2xl">
                                <button type="button" @click="showConsentModal = false" class="btn-ghost">Batal</button>
                                <button type="button" @click="agreedToConsent = true; showConsentModal = false" :disabled="!consent1 || !consent2" class="btn-primary" :class="(!consent1 || !consent2) ? 'opacity-50 cursor-not-allowed' : ''">Selesai</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function homecareForm() {
    return {
        step: {{ ($errors->has('complaint_description') || $errors->has('drug_allergies') || $errors->has('medical_image') || $errors->has('medical_document')) ? 2 : ($errors->any() ? 1 : 1) }},
        submitting: false,
        showConsentModal: false,
        agreedToConsent: false,
        consent1: false,
        consent2: false,
        imageFileName: null,
        docFileName: null,
        form: {
            full_name: @js(old('full_name', '')),
            whatsapp_number: @js(old('whatsapp_number', '')),
            age: @js(old('age', '')),
            gender: '{{ old('gender') }}',
            occupation: @js(old('occupation', '')),
            no_occupation: @js(old('occupation') === '-'),
            district: '{{ old('district') }}',
            village: '{{ old('village') }}',
            address: '{{ old('address') }}',
            complaint_description: @js(old('complaint_description', '')),
            drug_allergies: @js(old('drug_allergies', '')),
            no_allergies: @js(old('drug_allergies') === 'Tidak ada alergi obat'),
        },
        districts: [
            { name: 'Bekasi Barat', villages: ['Bintara', 'Bintara Jaya', 'Jakasampurna', 'Kota Baru', 'Kranji'] },
            { name: 'Bekasi Selatan', villages: ['Jakamulya', 'Jakasetia', 'Kayuringin Jaya', 'Marga Jaya', 'Pekayon Jaya'] },
            { name: 'Bekasi Timur', villages: ['Aren Jaya', 'Bekasi Jaya', 'Duren Jaya', 'Margahayu'] },
        ],
        selectedDistrict: '{{ old('district') }}',
        selectedVillage: '{{ old('village') }}',
        get availableVillages() {
            if (!this.selectedDistrict) return [];
            let d = this.districts.find(x => x.name === this.selectedDistrict);
            return d ? d.villages : [];
        },
        onDistrictChange() {
            this.selectedVillage = '';
            this.form.district = this.selectedDistrict;
            this.form.village = '';
        },
        onVillageChange() {
            this.form.village = this.selectedVillage;
        },
        nextStep() {
            if (this.step === 1) {
                if (!this.form.full_name || !this.form.whatsapp_number || !this.form.age || !this.form.gender || (!this.form.occupation && !this.form.no_occupation) || !this.form.district || !this.form.village || !this.form.address) {
                    alert('Mohon lengkapi semua data diri, wilayah, dan alamat lengkap.');
                    return;
                }
            }
            if (this.step === 2) {
                if (!this.form.complaint_description || this.form.complaint_description.length < 20) {
                    alert('Deskripsi keluhan minimal 20 karakter.');
                    return;
                }
                if (!this.form.drug_allergies || this.form.drug_allergies.trim() === '') {
                    alert('Mohon isi informasi alergi obat atau centang "Tidak ada alergi obat".');
                    return;
                }
            }
            this.step++;
        },
    };
}

document.getElementById('homecare-form').addEventListener('submit', function() {
    if (window.Alpine && document.querySelector('[x-data]').__x) {
        document.querySelector('[x-data]').__x.$data.submitting = true;
    }
});
</script>
@endpush

@endsection

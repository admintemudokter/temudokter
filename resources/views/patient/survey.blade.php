<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survei Kepuasan Pasien – Temu Dokter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-lg bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">
        
        {{-- Header --}}
        <div class="bg-brand-600 px-8 py-10 text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <div class="relative z-10">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514M9 11l-4 4"/>
                    </svg>
                </div>
                <h1 class="font-heading text-2xl font-bold text-white mb-2">Konsultasi Selesai</h1>
                <p class="text-white/80 text-sm">Bagaimana pengalaman Anda hari ini?</p>
            </div>
        </div>

        {{-- Body --}}
        <div class="p-8">
            @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-sm text-center">
                {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('patient.survey.store', $token) }}" method="POST" x-data="{ rating: 0, hoveredRating: 0, submitting: false }" @submit="submitting = true">
                @csrf
                
                {{-- Stars --}}
                <div class="flex flex-col items-center justify-center mb-8">
                    <p class="text-slate-500 font-medium text-sm mb-3">Berikan Penilaian (1-5)</p>
                    <div class="flex items-center gap-2">
                        <template x-for="i in 5">
                            <button type="button" 
                                    @click="rating = i" 
                                    @mouseenter="hoveredRating = i" 
                                    @mouseleave="hoveredRating = 0"
                                    class="transition-all transform hover:scale-110 focus:outline-none">
                                <svg class="w-10 h-10 transition-colors duration-200" 
                                     :class="(hoveredRating ? i <= hoveredRating : i <= rating) ? 'text-amber-400' : 'text-slate-200'" 
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        </template>
                    </div>
                    <input type="hidden" name="rating" x-model="rating">
                    @error('rating')
                        <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Review Text --}}
                <div class="mb-8">
                    <label class="form-label text-slate-700 font-medium" for="review">Tulis Ulasan / Saran (Opsional)</label>
                    <textarea name="review" id="review" rows="3" 
                              class="form-textarea mt-1 bg-slate-50 border-slate-200 focus:bg-white transition-colors" 
                              placeholder="Ceritakan pengalaman Anda berkonsultasi dengan dokter kami..."></textarea>
                    @error('review')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <button type="submit" 
                        class="btn-primary w-full py-4 text-base shadow-lg hover:shadow-xl transition-all"
                        :disabled="rating === 0 || submitting">
                    <span x-show="!submitting">Kirim Penilaian & Lanjut</span>
                    <span x-show="submitting" class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
                <p class="text-xs text-slate-400 text-center mt-4">
                    Ulasan Anda sangat berarti bagi kami untuk meningkatkan kualitas layanan Temu Dokter.
                </p>
            </form>
        </div>
    </div>

</body>
</html>

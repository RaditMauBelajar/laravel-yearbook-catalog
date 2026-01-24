@extends('layouts.admin')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Detail Calon Pelanggan</h1>
                <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-slate-600">
                    <span class="font-semibold text-slate-900">{{ $inquiry->name }}</span>
                    <span class="text-slate-300">•</span>
                    <span class="break-all">{{ $inquiry->email }}</span>
                    <span class="text-slate-300">•</span>
                    <span>{{ $inquiry->phone }}</span>
                </div>
                <p class="mt-1 text-sm text-slate-500">
                    Judul: <span class="text-slate-700">{{ $inquiry->subject }}</span>
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.inquiries.index') }}"
                   class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">
                    ← Kembali
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                <div class="font-semibold mb-1">Ada error:</div>
                <ul class="list-disc pl-5 text-sm space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    {{-- Layout --}}
    <div class="max-w-6xl mx-auto mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Info Card --}}
        <aside class="lg:col-span-1">
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
                <div class="p-5">
                    <h2 class="text-sm font-semibold text-slate-900">Informasi Kontak</h2>

                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-slate-500">Nama</dt>
                            <dd class="text-right font-medium text-slate-900">{{ $inquiry->name }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-slate-500">Email</dt>
                            <dd class="text-right font-medium text-slate-900 break-all">{{ $inquiry->email }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-slate-500">Telepon</dt>
                            <dd class="text-right font-medium text-slate-900">{{ $inquiry->phone }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-slate-500">Dibuat</dt>
                            <dd class="text-right font-medium text-slate-900">
                                {{ optional($inquiry->created_at)->format('d M Y H:i') }}
                            </dd>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-slate-500">Status</dt>
                            <dd class="text-right font-medium text-slate-900">
                                {{ $inquiry->status ?? '-' }}
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-5 flex flex-wrap gap-2">
                        @if(!empty($inquiry->email))
                            <a href="mailto:{{ $inquiry->email }}"
                               class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">
                                Email
                            </a>
                        @endif

                        @if(!empty($inquiry->phone))
                            <a href="https://wa.me/{{ preg_replace('/\D+/', '', $inquiry->phone) }}"
                               target="_blank"
                               class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-500">
                                WhatsApp
                            </a>
                        @endif
                    </div>

                    <div class="mt-6 rounded-xl bg-slate-50 p-4 text-xs text-slate-600">
                        Tips: follow-up via WhatsApp agar respons lebih cepat.
                    </div>
                </div>
            </div>
        </aside>

        {{-- Right: Chat --}}
        <section class="lg:col-span-2">
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">

                {{-- Chat header --}}
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Chat Thread</h2>
                        <p class="text-xs text-slate-500">Percakapan calon pelanggan & admin</p>
                    </div>
                    <div class="text-xs text-slate-500">
                        {{ $inquiry->messages->count() }} pesan
                    </div>
                </div>

                {{-- Chat body --}}
                <div id="chatBox" class="bg-slate-50 px-5 py-4 h-[420px] overflow-y-auto">
                    <div class="space-y-4">
                        @forelse($inquiry->messages as $m)
                            @php $isAdmin = $m->sender === 'admin'; @endphp

                            <div class="flex items-end gap-2 {{ $isAdmin ? 'justify-end' : 'justify-start' }}">
                                {{-- avatar --}}
                                <div class="{{ $isAdmin ? 'order-2' : '' }}">
                                    <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold
                                        {{ $isAdmin ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-700' }}">
                                        {{ $isAdmin ? 'A' : 'U' }}
                                    </div>
                                </div>

                                {{-- bubble --}}
                                <div class="max-w-[78%] sm:max-w-[65%]">
                                    <div class="text-[11px] mb-1 {{ $isAdmin ? 'text-right text-slate-500' : 'text-left text-slate-500' }}">
                                        <span class="font-semibold">{{ $isAdmin ? 'Admin' : 'User' }}</span>
                                        <span class="mx-1 text-slate-300">•</span>
                                        <span>{{ optional($m->created_at)->format('d M Y H:i') }}</span>
                                    </div>

                                    <div class="rounded-2xl px-4 py-3 shadow-sm ring-1
                                        {{ $isAdmin
                                            ? 'bg-indigo-600 text-white ring-indigo-600/20'
                                            : 'bg-white text-slate-800 ring-slate-200'
                                        }}">
                                        {{-- text (boleh kosong kalau foto-only) --}}
                                        @if(!empty($m->message))
                                            <p class="text-[15px] leading-6 whitespace-pre-wrap">{{ $m->message }}</p>
                                        @endif

                                        {{-- attachments --}}
                                        @if($m->attachments && $m->attachments->count())
                                            <div class="{{ !empty($m->message) ? 'mt-3' : '' }} grid grid-cols-2 sm:grid-cols-3 gap-2">
                                                @foreach($m->attachments as $att)
                                                    <a href="{{ asset('storage/'.$att->path) }}" target="_blank"
                                                       class="block overflow-hidden rounded-xl ring-1 ring-slate-200 hover:ring-slate-300 bg-white">
                                                        <img src="{{ asset('storage/'.$att->path) }}"
                                                             alt="{{ $att->original_name ?? 'Attachment' }}"
                                                             class="w-full h-28 object-cover" />
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- kalau kosong semua (harusnya tidak terjadi), kasih fallback --}}
                                        @if(empty($m->message) && (!($m->attachments && $m->attachments->count())))
                                            <p class="text-sm opacity-80">(pesan kosong)</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        @empty
                            <div class="py-16 text-center text-sm text-slate-500">
                                Belum ada pesan.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Reply --}}
                <div class="border-t border-slate-200 px-5 py-4 bg-white">
                    <form method="POST"
                          action="{{ route('admin.inquiries.reply', $inquiry->id) }}"
                          enctype="multipart/form-data"
                          class="space-y-3">
                        @csrf

                        <div>
                            <label class="text-sm font-medium text-slate-700">Balas ke User</label>
                            <textarea name="message" rows="3"
                                      class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-[15px] leading-6 text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                                      placeholder="Tulis balasan admin... (boleh kosong kalau kirim foto)">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium text-slate-700">Lampiran Foto (opsional)</label>
                                <span class="text-xs text-slate-500">Max 5 file, JPG/PNG/WEBP, 2MB/file</span>
                            </div>

                            <input id="attInput"
                                   type="file"
                                   name="attachments[]"
                                   multiple
                                   accept="image/png,image/jpeg,image/webp"
                                   class="block w-full text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800" />

                            @error('attachments')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('attachments.*')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            {{-- Preview filenames (client-side) --}}
                            <div id="attPreview" class="flex flex-wrap gap-2"></div>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit"
                                    class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                                Kirim Balasan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </section>
    </div>
</div>

<script>
(function () {
    // Auto scroll ke bawah
    const box = document.getElementById('chatBox');
    if (box) box.scrollTop = box.scrollHeight;

    // Preview nama file yang dipilih + remove item (tanpa library)
    const input = document.getElementById('attInput');
    const preview = document.getElementById('attPreview');

    if (!input || !preview) return;

    let files = [];

    function renderPreview(){
        preview.innerHTML = '';
        files.forEach((f, idx) => {
            const chip = document.createElement('div');
            chip.className = 'text-xs bg-slate-100 border border-slate-200 rounded-lg px-2 py-1 flex items-center gap-2';

            const name = document.createElement('span');
            name.textContent = f.name;

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'text-red-600 hover:text-red-700';
            btn.innerHTML = '<i class="fas fa-times"></i>';
            btn.addEventListener('click', () => {
                files.splice(idx, 1);

                // rebuild FileList
                const dt = new DataTransfer();
                files.forEach(x => dt.items.add(x));
                input.files = dt.files;

                renderPreview();
            });

            chip.appendChild(name);
            chip.appendChild(btn);
            preview.appendChild(chip);
        });
    }

    input.addEventListener('change', () => {
        files = Array.from(input.files || []).slice(0, 5);

        // enforce max 5
        const dt = new DataTransfer();
        files.forEach(x => dt.items.add(x));
        input.files = dt.files;

        renderPreview();
    });
})();
</script>
@endsection

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Book Catalog') }} - @yield('title', 'Yearbook Catalog')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50">
<div class="min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <i class="fas fa-book text-indigo-600 text-2xl"></i>
                        <span class="text-xl font-bold text-gray-900">Yearbook By Nostra</span>
                    </a>
                </div>

                {{-- Search Bar (Desktop) DIHAPUS sesuai permintaan --}}

                <!-- Right Menu -->
                <div class="flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-dashboard mr-2"></i>Dashboard
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-user-plus mr-2"></i>Daftar
                        </a>
                    @endguest
                </div>
            </div>

            <!-- Search Bar (SINGLE / MOBILE SEARCH) -->
            <div class="md:hidden pb-3">
                <form action="{{ route('home') }}" method="GET">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari buku tahunan..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </form>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    @include('components.alert')

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- About -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tentang Yearbook By Nostra</h3>
                    <p class="text-gray-600 text-sm">
                        Platform digital untuk menyimpan dan membaca buku tahunan sekolah.
                        Kenangan indah dapat diakses kapan saja, di mana saja.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-indigo-600">Beranda</a></li>
                        <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-indigo-600">Katalog Buku</a></li>
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <li><a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-indigo-600">Admin Panel</a></li>
                            @endif
                        @endauth
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Kontak</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-envelope mr-2 text-indigo-600"></i> info@yearbookhub.com</li>
                        <li><i class="fas fa-phone mr-2 text-indigo-600"></i> +62 123-456-7890</li>
                        <li><i class="fas fa-map-marker-alt mr-2 text-indigo-600"></i> Pekanbaru, Indonesia</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 mt-8 pt-6 text-center">
                <p class="text-gray-500 text-sm">
                    &copy; {{ date('Y') }} Yearbook By Nostra. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</div>

{{-- ============================================================
| FLOATING CHAT POPUP (LOGIN REQUIRED)
| - Guest: klik -> /chat (lempar login)
| - Auth : popup chat
| - Auto open kalau ?openChat=1
============================================================ --}}
<div
    x-data="chatPopup()"
    x-init="init()"
    class="fixed bottom-5 right-5 z-[9999]"
>
    {{-- Button Guest: arahkan ke chat.open (auth gate) --}}
    @guest
        <a
            href="{{ route('chat.open') }}"
            class="rounded-full shadow-lg bg-indigo-600 hover:bg-indigo-700 text-white w-14 h-14 flex items-center justify-center"
            aria-label="Chat Admin"
            title="Chat Admin"
        >
            <i class="fas fa-comment-dots text-xl"></i>
        </a>
    @endguest

    {{-- Button Auth: buka popup --}}
    @auth
        <button
            @click="toggle()"
            class="rounded-full shadow-lg bg-indigo-600 hover:bg-indigo-700 text-white w-14 h-14 flex items-center justify-center"
            aria-label="Chat Admin"
            title="Chat Admin"
        >
            <i class="fas fa-comment-dots text-xl"></i>
        </button>

        <!-- Panel -->
        <div
            x-show="open"
            x-transition
            class="mt-3 w-[360px] bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden"
        >
            <div class="px-4 py-3 bg-gray-50 border-b flex items-center justify-between">
                <div class="font-semibold text-gray-900">
                    Chat Admin
                    <span class="ml-2 text-xs text-gray-500 font-normal">Login required</span>
                </div>
                <button @click="close()" class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-4 space-y-3">
                <!-- Info First time -->
                <template x-if="ready && !hasInquiry">
                    <div class="space-y-2">
                        <div class="text-sm text-gray-700 font-medium">Mulai percakapan</div>
                        <input x-model="first.phone" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="No HP/WA" />
                        <input x-model="first.subject" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Judul (misal: Tanya harga yearbook)" />
                        <p class="text-xs text-gray-500">
                            Ini sekali aja buat konteks awal, setelah itu tinggal chat.
                        </p>
                    </div>
                </template>

                <!-- Messages box -->
                <div
                    x-ref="msgBox"
                    class="h-72 overflow-y-auto bg-gray-50 rounded-xl border p-3 space-y-2"
                >
                    <template x-if="!ready">
                        <div class="text-sm text-gray-500">Memuat chat...</div>
                    </template>

                    <template x-for="m in messages" :key="m.id">
                        <div class="flex" :class="m.sender === 'user' ? 'justify-end' : 'justify-start'">
                            <div
                                class="max-w-[80%] px-3 py-2 rounded-2xl text-sm space-y-2"
                                :class="m.sender === 'user' ? 'bg-indigo-600 text-white' : 'bg-white border text-gray-800'"
                            >
                                <template x-if="m.message">
                                    <div x-text="m.message"></div>
                                </template>

                                <template x-if="m.attachments && m.attachments.length">
                                    <div class="grid grid-cols-2 gap-2">
                                        <template x-for="a in m.attachments" :key="a.id">
                                            <a :href="a.url" target="_blank" class="block">
                                                <img :src="a.url" class="w-full h-24 object-cover rounded-lg border" />
                                            </a>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="ready && messages.length === 0">
                        <div class="text-sm text-gray-500">Belum ada pesan. Kirim pesan pertamamu ya.</div>
                    </template>
                </div>

                <!-- Input -->
                <div class="flex gap-2 items-center">
                    <input
                        x-ref="fileInput"
                        type="file"
                        class="hidden"
                        multiple
                        accept="image/png,image/jpeg,image/webp"
                        @change="pickFiles($event)"
                    />

                    <button
                        type="button"
                        @click="$refs.fileInput.click()"
                        class="px-3 py-2 rounded-lg border bg-white hover:bg-gray-50 text-gray-700"
                        title="Kirim Foto"
                    >
                        <i class="fas fa-paperclip"></i>
                    </button>

                    <input
                        x-model="draft"
                        @keydown.enter.prevent="send()"
                        class="flex-1 border rounded-lg px-3 py-2 text-sm"
                        placeholder="Tulis pesan..."
                    />

                    <button
                        @click="send()"
                        :disabled="sending"
                        class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm disabled:opacity-60"
                    >
                        <span x-show="!sending">Kirim</span>
                        <span x-show="sending">...</span>
                    </button>
                </div>

                <!-- Preview selected files -->
                <template x-if="files.length">
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(f, idx) in files" :key="idx">
                            <div class="text-xs bg-gray-100 border rounded-lg px-2 py-1 flex items-center gap-2">
                                <span x-text="f.name"></span>
                                <button class="text-red-600" @click="removeFile(idx)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    @endauth
</div>

@push('scripts')
<script>
function chatPopup(){
    return {
        open: false,
        ready: false,
        sending: false,

        hasInquiry: false,
        messages: [],
        draft: '',

        // attachments state
        files: [],
        poller: null,

        first: { phone: '', subject: '' },

        init(){
            const params = new URLSearchParams(window.location.search);
            if(params.get('openChat') === '1'){
                this.open = true;
                this.loadThread();
                this.startPolling();
            }
        },

        toggle(){
            this.open = !this.open;
            if(this.open){
                this.loadThread();
                this.startPolling();
            } else {
                this.stopPolling();
            }
        },

        close(){
            this.open = false;
            this.stopPolling();
        },

        startPolling(){
            if(this.poller) return;
            this.poller = setInterval(() => {
                if(this.open) this.loadThread();
            }, 3000);
        },

        stopPolling(){
            if(this.poller){
                clearInterval(this.poller);
                this.poller = null;
            }
        },

        async loadThread(){
            this.ready = false;
            try{
                const res = await fetch("{{ route('chat.thread') }}", {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();

                this.hasInquiry = !!data.hasInquiry;
                this.messages = data.messages || [];
            } catch(e){
                console.error(e);
                alert('Gagal memuat chat.');
            } finally {
                this.ready = true;
                this.$nextTick(() => this.scrollBottom());
            }
        },

        scrollBottom(){
            const box = this.$refs.msgBox;
            if(box) box.scrollTop = box.scrollHeight;
        },

        pickFiles(e){
            const picked = Array.from(e.target.files || []);
            this.files = [...this.files, ...picked].slice(0, 5);
            e.target.value = '';
        },

        removeFile(idx){
            this.files.splice(idx, 1);
        },

        async send(){
            const msg = (this.draft || '').trim();

            // boleh kirim tanpa teks kalau ada foto
            if(!msg && this.files.length === 0) return;

            if(!this.hasInquiry){
                if(!this.first.phone.trim() || !this.first.subject.trim()){
                    alert('Isi No HP/WA dan Judul dulu ya.');
                    return;
                }
            }

            this.sending = true;
            try{
                const fd = new FormData();
                if(msg) fd.append('message', msg);

                if(!this.hasInquiry){
                    fd.append('phone', this.first.phone.trim());
                    fd.append('subject', this.first.subject.trim());
                }

                this.files.forEach(f => fd.append('attachments[]', f));

                const res = await fetch("{{ route('chat.send') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: fd
                });

                if(!res.ok){
                    const data = await res.json().catch(()=>null);
                    alert(data?.message ?? 'Gagal mengirim pesan.');
                    return;
                }

                const data = await res.json();
                if(data?.message){
                    this.messages.push(data.message);
                }

                this.draft = '';
                this.files = [];
                this.hasInquiry = true;

                this.$nextTick(() => this.scrollBottom());
            } catch(e){
                console.error(e);
                alert('Gagal mengirim pesan.');
            } finally {
                this.sending = false;
            }
        }
    }
}
</script>
@endpush

@stack('scripts')
</body>
</html>

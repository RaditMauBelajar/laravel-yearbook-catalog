@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-white">Calon Pelanggan</h1>
    </div>

    <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-800 text-gray-200">
                <tr>
                    <th class="text-left px-4 py-3">Nama</th>
                    <th class="text-left px-4 py-3">Judul</th>
                    <th class="text-left px-4 py-3">Status</th>
                    <th class="text-left px-4 py-3">Masuk</th>
                    <th class="text-left px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-300">
                @forelse($inquiries as $inq)
                    <tr class="border-t border-gray-800">
                        <td class="px-4 py-3">{{ $inq->name }}</td>
                        <td class="px-4 py-3">{{ $inq->subject }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs
                                {{ $inq->status === 'new' ? 'bg-yellow-600 text-white' : 'bg-green-600 text-white' }}">
                                {{ $inq->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $inq->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.inquiries.show', $inq->id) }}"
                               class="inline-block px-3 py-1 rounded bg-indigo-600 hover:bg-indigo-700 text-white">
                                Lihat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr class="border-t border-gray-800">
                        <td colspan="5" class="px-4 py-6 text-center text-gray-400">
                            Belum ada calon pelanggan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $inquiries->links() }}
    </div>
</div>
@endsection

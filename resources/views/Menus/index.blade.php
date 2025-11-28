@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded-2xl shadow-lg">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Daftar Menu 🍽️</h1>
        <a href="{{ route('menus.create') }}" class="px-4 py-2 bg-black text-white rounded-lg">
           + Tambah Menu
        </a>
    </div>

    @if(session('success'))
    <div class="p-3 mb-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    <table class="w-full text-left">
        <thead>
            <tr class="border-b">
                <th class="py-2">ID</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($menus as $m)
            <tr class="border-b">
                <td class="py-2">{{ $m->id }}</td>
                <td>{{ $m->nama_menu }}</td>
                <td>{{ $m->category->nama_kategori ?? '-' }}</td>
                <td>Rp {{ number_format($m->harga, 0, ',', '.') }}</td>
                <td>{{ $m->stok }}</td>
                <td>
                    @if($m->status)
                        <span class="text-green-600 font-semibold">Tersedia</span>
                    @else
                        <span class="text-red-600 font-semibold">Habis</span>
                    @endif
                </td>
                <td>
                    @if($m->image)
                        <img src="{{ $m->image }}" class="w-12 h-12 rounded object-cover">
                    @else
                        -
                    @endif
                </td>
                <td class="flex gap-2 items-center">
                    <a href="{{ route('menus.edit', $m) }}" class="text-blue-600">Edit</a>

                    <form action="{{ route('menus.destroy', $m) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600"
                        onclick="return confirm('Yakin mau hapus menu ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection

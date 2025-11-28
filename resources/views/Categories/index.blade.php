@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white rounded-2xl shadow-lg">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Kategori Menu 🍜</h1>
        <a href="{{ route('categories.create') }}" 
           class="px-4 py-2 bg-black text-white rounded-lg">
           + Tambah Kategori
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
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($categories as $cat)
            <tr class="border-b">
                <td class="py-2">{{ $cat->id }}</td>
                <td>{{ $cat->nama_kategori }}</td>
                <td class="flex gap-2 items-center">
                    <a href="{{ route('categories.edit', $cat) }}"
                       class="text-blue-600">Edit</a>

                    <form action="{{ route('categories.destroy', $cat) }}" 
                          method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600"
                        onclick="return confirm('Yakin mau hapus?')">Hapus</button>
                    </form>

                    @if($cat->trashed())
                    <form action="{{ route('categories.restore', $cat->id) }}"
                          method="POST">
                        @csrf
                        <button class="text-green-700">Restore</button>
                    </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection

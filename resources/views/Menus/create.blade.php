@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white rounded-2xl shadow">
    <h1 class="text-xl font-bold mb-4">Tambah Menu</h1>

    <form action="{{ route('menus.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
    @csrf

    <input type="text" name="nama_menu" class="w-full border rounded p-2" placeholder="Nama menu..." value="{{ old('nama_menu') }}">

    <select name="category_id" class="w-full border rounded p-2">
        <option value="">— Pilih kategori —</option>
        @foreach($categories as $c)
            <option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>
                {{ $c->nama_kategori }}
            </option>
        @endforeach
    </select>

    <input type="number" name="harga" class="w-full border rounded p-2" placeholder="Harga" value="{{ old('harga') }}">
    <input type="number" name="stok" class="w-full border rounded p-2" placeholder="Stok" value="{{ old('stok') }}">

    <select name="status" class="w-full border rounded p-2">
        <option value="1">Tersedia</option>
        <option value="0">Habis</option>
    </select>

    <!-- UPLOAD LOCAL IMAGE -->
    <input type="file" name="image" class="w-full border rounded p-2">

    @error('image')
        <p class="text-red-600 text-sm">{{ $message }}</p>
    @enderror

    <button class="w-full bg-black text-white py-2 rounded-lg">Simpan</button>
</form>

</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white rounded-2xl shadow">
    <h1 class="text-xl font-bold mb-4">Edit Menu</h1>

    <form action="{{ route('menus.update', $menu) }}" method="POST" class="space-y-4" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="text" name="nama_menu" class="w-full border rounded p-2" value="{{ old('nama_menu', $menu->nama_menu) }}">

    <select name="category_id" class="w-full border rounded p-2">
        @foreach($categories as $c)
            <option value="{{ $c->id }}" @selected($menu->category_id == $c->id)>
                {{ $c->nama_kategori }}
            </option>
        @endforeach
    </select>

    <input type="number" name="harga" class="w-full border rounded p-2" value="{{ old('harga', $menu->harga) }}">
    <input type="number" name="stok" class="w-full border rounded p-2" value="{{ old('stok', $menu->stok) }}">

    <select name="status" class="w-full border rounded p-2">
        <option value="1" @selected($menu->status==1)>Tersedia</option>
        <option value="0" @selected($menu->status==0)>Habis</option>
    </select>

    <!-- Optional ganti gambar -->
    <input type="file" name="image" class="w-full border rounded p-2">

    @if($menu->image)
        <img src="{{ $menu->image }}" class="mt-2 w-20 h-20 rounded object-cover">
    @endif

    <button class="w-full bg-black text-white py-2 rounded-lg">Update</button>
</form>

</div>
@endsection

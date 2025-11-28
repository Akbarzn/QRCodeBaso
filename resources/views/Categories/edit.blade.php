@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6 bg-white rounded-2xl shadow">
    <h1 class="text-xl font-bold mb-4">Edit Kategori</h1>

    <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-3">
        @csrf
        @method('PUT')

        <input type="text" name="nama_kategori"
               class="w-full border rounded p-2"
               value="{{ old('nama_kategori', $category->nama_kategori) }}">

        @error('nama_kategori')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror

        <button class="w-full bg-black text-white py-2 rounded-lg">
            Update
        </button>
    </form>
</div>
@endsection

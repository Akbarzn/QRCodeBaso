@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white rounded-2xl shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Tambah Transaksi Manual / Midtrans</h1>

    <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium mb-1">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan"
                   class="w-full border rounded-lg p-2" required>
        </div>

        <div>
            <label class="block font-medium mb-1">Nomor Meja</label>
            <input type="text" name="nomor_meja"
                   class="w-full border rounded-lg p-2" required>
        </div>

        <div>
            <label class="block font-medium mb-1">Metode Pembayaran</label>
            <select name="metode_pembayaran" class="w-full border rounded-lg p-2" required>
                <option value="manual">Manual</option>
                <option value="midtrans">Midtrans</option>
            </select>
        </div>

        <div>
            <label class="block font-medium mb-2">Pilih Menu Pesanan</label>
            <div class="grid gap-3">
           @foreach($menus as $menu)
<div class="flex items-center gap-3 border p-3 rounded-xl">
    <input type="checkbox" name="menus[{{ $menu->id }}][menu_id]" value="{{ $menu->id }}">
    <div class="flex-1">
        <p class="font-semibold">{{ $menu->nama_menu }}</p>
        <p class="text-sm text-gray-500">Rp {{ number_format($menu->harga,0,',','.') }}</p>
        <!-- snapshot harga ikut terkirim dari UI -->
        <input type="hidden" name="menus[{{ $menu->id }}][harga]" value="{{ $menu->harga }}">
    </div>
    <input type="number" name="menus[{{ $menu->id }}][jumlah]" value="1"
           min="1" class="w-20 border rounded p-1">

    <input type="text" name="menus[{{ $menu->id }}][catatan]" placeholder="catatan..."
           class="border rounded p-1 text-sm w-40">
</div>
@endforeach


            </div>
        </div>

        <button class="w-full bg-black text-white py-2 rounded-lg font-semibold">
            Simpan
        </button>
    </form>
</div>
@endsection

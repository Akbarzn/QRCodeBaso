@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-2xl shadow-md">

    <!-- ✅ Header -->
    <div class="text-center mb-4">
        <div class="text-green-500 text-5xl mb-2">✔</div>
        <h1 class="text-2xl font-bold">Pesanan Berhasil Dibuat 🎉</h1>
        <p class="text-sm text-gray-500">
            Pembayaran: <span class="font-semibold">{{ strtoupper($transaction->payment_status) }}</span>
            (Menunggu konfirmasi kasir)
        </p>
    </div>

    <!-- 🧾 STRUK PESANAN -->
    <div class="border rounded-xl p-4 bg-gray-50 text-sm">
        <h2 class="font-bold mb-2">Struk Pesanan</h2>

        <p><b>Kode:</b> {{ $transaction->kode_transaksi }}</p>
        <p><b>Nama:</b> {{ $transaction->nama_pelanggan }}</p>
        <p><b>Meja:</b> {{ $transaction->nomor_meja }}</p>
        <p><b>Metode:</b> {{ ucfirst($transaction->metode_pembayaran) }}</p>

        <hr class="my-2">

        <h3 class="font-semibold mb-1">Detail Menu:</h3>
        <ul class="mb-2">
            @foreach($transaction->menuTransactions as $mt)
                <li>• {{ $mt->menu->nama_menu }}
                    (x{{ $mt->jumlah }}) → Rp {{ number_format($mt->subtotal,0,',','.') }}
                    <br>
                    <span class="text-xs text-gray-500">Catatan: {{ $mt->catatan ?? '-' }}</span>
                </li>
            @endforeach
        </ul>

        <hr class="my-2">

        <p class="font-bold text-right">TOTAL: Rp {{ number_format($transaction->total_harga,0,',','.') }}</p>
    </div>

    <!-- Opsional: QRIS Manual (kalau mau dipakai kasir buat scan) -->
    @if($transaction->metode_pembayaran === 'manual')
    <div class="mt-4">
        <p class="text-sm font-semibold mb-2 text-center">QRIS Manual (opsional scan kasir)</p>
        <div class="flex justify-center">
            <img src="/images/qris.png" class="w-40 rounded-xl" alt="QRIS">
        </div>
        <p class="text-xs text-gray-400 text-center mt-1">Ini hanya tampilan contoh QRIS local</p>
    </div>
    @endif

    <!-- Tombol kembali -->
    <div class="mt-6 text-center">
        <a href="{{ route('transactions.index') }}" class="px-4 py-2 bg-black text-white rounded-xl text-sm">
            ← Kembali ke Daftar Transaksi
        </a>
    </div>

</div>
@endsection

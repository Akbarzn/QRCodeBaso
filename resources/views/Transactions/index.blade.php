@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded-2xl shadow-lg">

    <h1 class="text-2xl font-bold mb-4">Daftar Transaksi</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 p-2 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 p-2 rounded mb-3">
            {{ session('error') }}
        </div>
    @endif

    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3">Kode</th>
                <th>Nama</th>
                <th>Meja</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($transactions as $t)
            <tr class="border-b">
                <td class="py-3 font-mono">{{ $t->kode_transaksi }}</td>
                <td>{{ $t->nama_pelanggan ?? '-' }}</td>
                <td>{{ $t->nomor_meja ?? '-' }}</td>
                <td>{{ ucfirst($t->metode_pembayaran) }}</td>
                <td>{{ ucfirst($t->status) }}</td>
                <td>{{ strtoupper($t->payment_status) }}</td>
                <td>Rp {{ number_format($t->total_harga,0,',','.') }}</td>

                <td class="flex gap-2 items-center py-2">

                    <!-- 🔥 Konfirmasi Manual -->
                    @if($t->metode_pembayaran === 'manual' && $t->payment_status === 'pending')
                    <form action="{{ route('transactions.confirmPayment', $t) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="px-3 py-1 bg-green-600 text-white rounded text-xs"
                                onclick="return confirm('Konfirmasi pembayaran manual?')">
                                ✅ Konfirmasi Bayar
                        </button>
                    </form>
                    @endif

                    <!-- Detail -->
                    <a href="{{ route('transactions.show', $t) }}" class="text-blue-600 hover:underline text-xs">Detail</a>

                </td>
            </tr>

        @empty
            <tr>
                <td colspan="8" class="py-6 text-center text-gray-500">Kosong 💀</td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>
@endsection

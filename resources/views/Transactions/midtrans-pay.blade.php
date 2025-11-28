@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6 bg-white rounded-2xl shadow-lg">
    <h1 class="text-2xl font-bold mb-2">Pembayaran via Midtrans</h1>
    <p class="mb-4 text-gray-600">Kode: <span class="font-mono">{{ $transaction->kode_transaksi }}</span></p>

    <div class="border p-4 rounded-lg mb-4">
        <p>Nama: <b>{{ $transaction->nama_pelanggan }}</b></p>
        <p>Meja: <b>{{ $transaction->nomor_meja }}</b></p>
        <p>Total Saat Ini: <b>Rp {{ number_format($transaction->total_harga,0,',','.') }}</b></p>
    </div>

    <!-- tombol sementara: diarahkan ke snap_url jika sudah ada -->
    @if($transaction->snap_url)
        <a href="{{ $transaction->snap_url }}" target="_blank"
           class="w-full block text-center bg-green-600 text-white py-2 rounded-lg">
            Bayar Sekarang
        </a>
    @else
        <button disabled class="w-full bg-gray-300 text-gray-500 py-2 rounded-lg">
            Snap URL belum tersedia
        </button>
    @endif

    <a href="{{ route('transactions.index') }}" class="block text-center mt-3 text-blue-600">
        ← Kembali ke daftar
    </a>
</div>
        <script>
    // 🚀 AUTO REDIRECT ke pembayaran Midtrans
    setTimeout(() => {
      window.location = "{{ $transaction->snap_url }}";
    }, 1000);

    document.addEventListener("DOMContentLoaded", () => {
  new Audio("{{ asset('storage/notif.mp3') }}").play(); // bunyi saat masuk success

  // tombol confirm bayar dari admin (nanti dipakai di UI notif)
  document.body.addEventListener("click", async (e) => {
    if (e.target.classList.contains("btn-confirm")) {
      const id = e.target.dataset.id;
      await fetch(`/panel/transactions/${id}/confirm-payment`, {
        method: "PATCH",
        headers: {
           "X-CSRF-TOKEN": "{{ csrf_token() }}",
           "Content-Type": "application/json"
        }
      });
      alert("Pembayaran dikonfirmasi admin ✅");
    }
  });
});

    </script>
@endsection

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<script src="https://cdn.tailwindcss.com"></script>
<title>Detail Invoice</title>
</head>
<body class="bg-orange-50 min-h-screen flex justify-center items-center p-5">

<div class="bg-white rounded-3xl shadow-xl w-full max-w-xl p-6 border-t-8 border-orange-600 space-y-4">

  <h1 class="text-2xl font-extrabold text-center text-orange-600">🧾 Detail Invoice</h1>

  <div class="bg-gray-100 p-4 rounded-2xl border">
    <p class="text-sm text-gray-600">Nomor Meja</p>
    <p class="text-lg font-extrabold text-gray-800">{{ $transaction->nomor_meja }}</p>

    <p class="text-sm text-gray-600 mt-2">Kode Transaksi</p>
    <p class="text-lg font-extrabold text-orange-600">{{ $transaction->kode_transaksi }}</p>

    <p class="text-sm text-gray-600 mt-2">Total Harga</p>
    <p class="text-xl font-extrabold text-gray-800">Rp {{ number_format($transaction->total_harga,0,',','.') }}</p>

    <p class="text-sm text-gray-600 mt-2">Status Pembayaran</p>
    <p class="text-lg font-bold">{{ strtoupper($transaction->payment_status) }}</p>
  </div>

  <!-- LIST PESANAN -->
  <div class="space-y-2">
    @foreach($transaction->menuTransactions as $item)
    <div class="flex items-center justify-between bg-orange-50 border-l-4 border-orange-600 p-3 rounded-xl shadow-sm">
      <div class="flex items-center gap-3">
        <img src="{{ asset($item->menu->image) }}"
          class="w-12 h-12 object-cover rounded-xl"/>
        <div>
          <p class="text-sm font-extrabold text-gray-800">{{ $item->menu->nama_menu }}</p>
          <p class="text-xs text-orange-600">{{ $item->jumlah }} × Rp {{ number_format($item->harga,0,',','.') }}</p>
          <p class="text-xs text-gray-600">{{ $item->catatan }}</p>
        </div>
      </div>
      <p class="text-sm font-extrabold text-gray-800">Rp {{ number_format($item->subtotal,0,',','.') }}</p>
    </div>
    @endforeach
  </div>

  <!-- TOMBOL KONFIRMASI PEMBAYARAN -->
  {{-- @if($transaction->metode_pembayaran === 'manual' && $transaction->payment_status !== 'paid')
  <button onclick="confirmManual()"
    class="w-full bg-black text-white p-4 rounded-full font-extrabold text-lg hover:scale-105 transition shadow">
    Konfirmasi Pembayaran ke Kasir ✅
  </button>
  <p class="text-xs text-center text-gray-500">
    Pesanan akan diproses setelah konfirmasi oleh kasir.
  </p> --}}
  @if($transaction->metode_pembayaran === 'manual' && $transaction->payment_status === 'pending')
                    <form action="{{ route('transactions.confirmPayment', $transaction) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="px-3 py-1 bg-green-600 text-white rounded text-xs"
                                onclick="return confirm('Konfirmasi pembayaran manual?')">
                                ✅ Konfirmasi Bayar
                        </button>
                    </form>
  @endif

  <!-- Jika midtrans & belum bayar -->
  @if($transaction->metode_pembayaran === 'midtrans' && $transaction->payment_status === 'pending')
  <button onclick="location.href='{{ $transaction->snap_url }}'"
    class="w-full bg-orange-600 text-white p-4 rounded-full font-extrabold text-lg hover:scale-105 transition shadow">
    Bayar Sekarang 💳
  </button>
  <p class="text-xs text-center text-gray-500">
    Silahkan lakukan pembayaran via Midtrans.
  </p>
  @endif

  <!-- Jika paid -->
  @if($transaction->payment_status === 'paid')
  <p class="text-center text-sm text-green-600 font-extrabold">
    ✅ Sudah Dibayar, Pesanan Sedang Diproses/Dikirim
  </p>
  @endif

</div>

<script>
function confirmManual() {
  // 🔊 suara notif (HARUS interaksi user)
  new Audio("{{ asset('storage/notif.mp3') }}").play();

  fetch("{{ route('transactions.confirmPayment', $transaction->id) }}", {
    method:"PATCH",
    headers:{
      "X-CSRF-TOKEN":"{{ csrf_token() }}"
    }
  })
  .then(r => r.json())
  .then(() => {
    window.location = "{{ route('customer.success', $transaction->id) }}";
  })
  .catch(e => alert("Gagal konfirmasi ❌"))
}
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
<title>Order Berhasil</title>
</head>
<body class="bg-orange-50 min-h-screen flex justify-center items-center p-6">

<div class="bg-white rounded-3xl shadow-xl w-full max-w-lg p-6 text-left border-t-8 border-orange-600 space-y-5">

  <h1 class="text-3xl font-extrabold text-center text-orange-600">🎉 Order Berhasil!</h1>

  <!-- Informasi utama -->
  <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200">
    <p class="text-sm font-bold text-gray-600">Kode Transaksi (Invoice)</p>
    <p class="text-2xl font-extrabold text-orange-600">{{ $transaction->kode_transaksi }}</p>

    <p class="text-sm font-bold text-gray-600 mt-4">Nomor Meja</p>
    <p class="text-2xl font-extrabold text-gray-800">{{ $transaction->nomor_meja }}</p>

    <p class="text-sm font-bold text-gray-600 mt-4">Status Pembayaran</p>
    <p class="text-xl font-extrabold text-gray-800">
      {{ strtoupper($transaction->payment_status) }}
    </p>
  </div>

  <!-- Detail Pesanan -->
  <div>
    <h2 class="text-2xl font-extrabold text-orange-600 mb-3">🛍 Detail Pesanan</h2>
    <div class="space-y-3">

      @foreach($transaction->menuTransactions as $item)
      <div class="flex justify-between items-center bg-orange-50 border border-orange-200 p-4 rounded-2xl shadow-sm">
        <div class="flex items-center gap-3">
          <img src="{{ asset('storage/menu-images/'.$item->menu->image) }}"
               class="w-16 h-16 object-cover rounded-xl border shadow">

          <div>
            <p class="text-lg font-extrabold text-gray-800">{{ $item->menu->nama_menu }}</p>
            <p class="text-sm font-bold text-orange-600">{{ $item->jumlah }} × Rp {{ number_format($item->harga,0,',','.') }}</p>
            @if($item->catatan)
              <p class="text-xs text-gray-600">Catatan: {{ $item->catatan }}</p>
            @endif
          </div>
        </div>
        <div class="text-right">
          <p class="text-xs font-bold text-gray-600">Subtotal</p>
          <p class="text-lg font-extrabold text-gray-800">Rp {{ number_format($item->subtotal,0,',','.') }}</p>
        </div>
      </div>
      @endforeach

    </div>
  </div>

  <!-- Total -->
  <div class="bg-orange-600 text-white p-5 rounded-3xl text-center shadow">
    <p class="text-xs opacity-90">Total Bayar</p>
    <p class="text-4xl font-extrabold">Rp {{ number_format($transaction->total_harga,0,',','.') }}</p>
  </div>

  <!-- Instruksi pembayaran -->
  <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 p-4 rounded-2xl shadow-sm">
    <p class="font-bold">💡 Instruksi Pembayaran</p>
    @if($transaction->metode_pembayaran === 'manual')
      <p class="text-sm mt-1">
        Silakan lakukan pembayaran ke kasir. Pesanan anda akan diproses setelah melakukan pembayaran.
      </p>
    @else
      <p class="text-sm mt-1">
        Silakan konfirmasi pembayaran ke kasir jika sudah membayar melalui sistem payment.
      </p>
    @endif
  </div>

  <!-- Note process -->
  <p class="text-sm text-gray-600 border-l-4 border-orange-500 pl-3 italic">
    Pesanan anda akan diproses setelah pembayaran selesai. ⏳ Terima kasih sudah order! 🍜
  </p>

  <!-- CTA kembali -->
  <a href="{{ route('customer.menu') }}"
    class="block text-center bg-black text-white p-3 rounded-full font-extrabold hover:scale-105 transition shadow-lg">
    Kembali ke Menu
  </a>

</div>

</body>
</html>

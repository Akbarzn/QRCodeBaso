<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Checkout</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
/* HAPUS SPINNER PANAH ↑ ↓ PADA INPUT NUMBER */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none !important;
  margin: 0 !important;
}
input[type=number] {
  -moz-appearance: textfield !important;
  appearance: none !important;
}
</style>
</head>

<body class="bg-orange-50 min-h-screen flex justify-center items-center p-6">
<div class="bg-white rounded-3xl shadow-xl w-full max-w-xl p-6 border-t-8 border-orange-600">

<form id="order-form" action="{{ route('customer.order') }}" method="POST" class="space-y-4">
@csrf

<!-- INPUT FORM -->
<div>
  <label class="block text-sm font-bold text-gray-600 ml-1">Nomor Meja</label>
  <input type="text" name="nomor_meja" required
    class="w-full border border-gray-300 rounded-2xl p-4 text-lg font-extrabold text-gray-800 outline-none focus:ring-2 focus:ring-orange-400 shadow-sm"
    placeholder="Masukkan nomor meja..."
    value="{{ old('nomor_meja', $nomor_meja ?? '') }}">
</div>

<div>
  <label class="block text-sm font-bold text-gray-600 ml-1">Nama Pelanggan</label>
  <input type="text" name="nama_pelanggan" required
    class="w-full border border-gray-300 rounded-2xl p-4 text-lg font-extrabold text-gray-800 outline-none focus:ring-2 focus:ring-orange-400 shadow-sm"
    placeholder="Masukkan nama kamu..."
    value="{{ old('nama_pelanggan') }}">
</div>

<div>
  <label class="block text-sm font-bold text-gray-600 ml-1">Metode Pembayaran</label>
  <select name="metode_pembayaran" required
    class="w-full border border-gray-300 rounded-2xl p-4 text-lg font-extrabold text-gray-800 outline-none focus:ring-2 focus:ring-orange-400 shadow-sm">
    <option value="manual">Cash / Manual</option>
    <option value="midtrans">QRIS / E-Wallet</option>
  </select>
</div>

<!-- KODE TRANSAKSI DITAMPILKAN SAJA READONLY -->
<div class="bg-gray-100 p-4 rounded-2xl border border-gray-200">
  <label class="block text-sm font-bold text-gray-600 ml-1">Invoice</label>
  <input type="text"
    class="w-full border rounded-xl bg-white p-3 text-xl font-extrabold text-gray-800 outline-none opacity-80 cursor-not-allowed"
    value="{{ $kode_transaksi }}" readonly>
</div>

<!-- Hidden untuk kirim invoice ke DB -->
<input type="hidden" name="kode_transaksi" value="{{ $kode_transaksi }}">

<!-- DETAIL PESANAN DI PALING BAWAH -->
<div class="mt-4 pt-2 border-t border-orange-200">
  <h2 class="text-xl font-extrabold text-orange-600 mb-3">🛍 Detail Pesanan</h2>
  <div class="space-y-3">

    @php $grandTotal = 0; @endphp
    @foreach(session('menus', []) as $i => $item)
      @php $grandTotal += $item['subtotal']; @endphp

      <div class="flex justify-between items-center border border-orange-200 p-3 rounded-2xl bg-orange-50">
        <div class="flex items-center gap-3">
          <!-- Gambar dari STORAGE kamu -->
          {{-- <img
            src="{{ asset('storage/menu-images/'.$item['image']) }}"
            alt="gambar menu"
            class="w-16 h-16 object-cover rounded-xl border border-orange-300 shadow"
          /> --}}
          <div>
            <p class="font-extrabold text-gray-800">{{ $item['nama_menu'] }}</p>
            <p class="text-sm font-bold text-orange-600">{{ $item['jumlah'] }} × Rp {{ number_format($item['harga'],0,',','.') }}</p>
          </div>
        </div>

        <div class="text-right">
          <p class="text-xs font-bold text-gray-600">Subtotal</p>
          <p class="font-extrabold text-gray-800">Rp {{ number_format($item['subtotal'],0,',','.') }}</p>
        </div>

        <!-- Hidden per menu untuk dikirim ke controller store() -->
        <input type="hidden" name="menus[{{ $i }}][menu_id]"  value="{{ $item['menu_id'] }}">
        <input type="hidden" name="menus[{{ $i }}][jumlah]"   value="{{ $item['jumlah'] }}">
        <input type="hidden" name="menus[{{ $i }}][catatan]"  value="{{ $item['catatan'] ?? null }}">
        <input type="hidden" name="menus[{{ $i }}][harga]"    value="{{ $item['harga'] }}">
        <input type="hidden" name="menus[{{ $i }}][subtotal]" value="{{ $item['subtotal'] }}">
      </div>

    @endforeach

  </div>
</div>

<!-- TOTAL HARGA -->
<div class="bg-orange-600 text-white p-4 rounded-3xl text-center shadow-lg">
  <p class="text-sm opacity-90">Total Bayar</p>
  <p class="text-3xl font-extrabold">Rp {{ number_format($grandTotal,0,',','.') }}</p>
</div>

<!-- BUTTON SUBMIT -->
<button class="w-full bg-black text-white p-4 rounded-2xl font-extrabold text-xl hover:scale-105 transition shadow-lg">
  Pesan Sekarang ✅
</button>

</form>

</div>
</body>
</html>

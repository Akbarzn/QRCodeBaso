<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Pilih Menu</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
/* Hilangkan spinner panah */
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
<body class="bg-gray-100 min-h-screen">

<div class="max-w-5xl mx-auto pt-6 pb-52 px-4">
  <h1 class="text-3xl font-extrabold text-center text-orange-600 mb-6">🍜 Pilih Menu</h1>

  <!-- 1 form saja -->
  <form id="order-form" action="{{ route('customer.checkout') }}" method="POST">
    @csrf
  </form>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($menus as $i => $menu)
    <div class="menu-card bg-white rounded-3xl shadow-lg p-5 flex flex-col"
         data-harga="{{ $menu->harga }}">

      <!-- Gambar dari storage -->
      <div class="rounded-2xl overflow-hidden mb-3 border shadow-sm">
        <img src="{{ asset($menu->image) }}"
             class="w-full h-56 object-cover"/>
      </div>

      <div class="flex-1">
        <p class="text-xl font-extrabold text-gray-800">{{ $menu->nama_menu }}</p>
        <p class="text-lg font-extrabold text-orange-500">Rp {{ number_format($menu->harga,0,',','.') }}</p>
      </div>

      <!-- Kontrol jumlah mulai dari 0 -->
      <div class="flex items-center gap-3 mt-4 justify-center">
        <button type="button" class="btn-minus w-10 h-10 bg-orange-200 text-orange-700 rounded-full text-xl font-extrabold shadow">−</button>

        <input type="number" name="menus[{{ $i }}][jumlah]" 
               form="order-form"
               value="0" min="0"
               class="input-jumlah w-16 text-center text-lg font-extrabold bg-orange-50 border rounded-xl shadow-sm outline-none"/>

        <button type="button" class="btn-plus w-10 h-10 bg-orange-200 text-orange-700 rounded-full text-xl font-extrabold shadow">+</button>

        <input type="hidden" name="menus[{{ $i }}][menu_id]" 
               form="order-form" value="{{ $menu->id }}">
        <input type="hidden" name="menus[{{ $i }}][harga]" 
               form="order-form" value="{{ $menu->harga }}">
      </div>

    </div>
    @endforeach
  </div>
</div>

<!-- Bottom bar Total mulai dari 0 -->
<div class="fixed bottom-0 left-0 right-0 bg-orange-600 text-white px-6 py-4 rounded-t-3xl shadow-2xl">
  <div class="max-w-5xl mx-auto flex justify-between items-center">
    <div>
      <p class="text-sm opacity-90">Total Harga</p>
      <p id="total-harga" class="text-3xl font-extrabold">Rp 0</p>
    </div>
    <button onclick="document.getElementById('order-form').submit()"
       class="bg-white text-orange-600 px-6 py-3 rounded-full font-extrabold shadow-lg hover:scale-105 transition text-lg">
      Checkout 🛒
    </button>
  </div>
</div>

<!-- Script hitung total mulai dari 0 -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const cards = document.querySelectorAll(".menu-card");
  const totalEl = document.getElementById("total-harga");

  function updateTotal() {
    let total = 0;
    cards.forEach(card => {
      const harga = parseFloat(card.dataset.harga) || 0;
      const jumlah = parseInt(card.querySelector(".input-jumlah").value) || 0;
      total += harga * jumlah;
    });
    totalEl.innerText = "Rp " + total.toLocaleString("id-ID");
  }

  document.body.addEventListener("click", (e) => {
    if (e.target.classList.contains("btn-plus") || e.target.classList.contains("btn-minus")) {
      const card = e.target.closest(".menu-card");
      const input = card.querySelector(".input-jumlah");
      let val = parseInt(input.value) || 0;

      if (e.target.classList.contains("btn-plus")) val++;
      if (e.target.classList.contains("btn-minus") && val > 0) val--;

      input.value = val;
      updateTotal();
    }
  });

  document.body.addEventListener("input", (e) => {
    if (e.target.classList.contains("input-jumlah")) updateTotal();
  });

  updateTotal();
});
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Baso QR') }}</title>
    <!-- Load Tailwind + Vite CSS/JS Breeze -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">

<!-- ✅ NAVBAR ADMIN + NOTIF DROPDOWN -->
<nav class="bg-white shadow px-6 py-4 mb-5">
    <div class="max-w-7xl mx-auto flex justify-between items-center">

        <!-- LEFT SIDE -->
        <div class="flex items-center gap-6">
            <a href="/panel/categories" class="font-bold text-xl text-orange-600">🍜 Baso QR</a>
            <a href="{{ route('categories.index') }}" class="font-semibold hover:text-orange-500 transition">Category</a>
            <a href="{{ route('menus.index') }}" class="font-semibold hover:text-orange-500 transition">Menu</a>
            <a href="{{ route('transactions.index') }}" class="font-semibold hover:text-orange-500 transition">Transaction</a>
        </div>

        <!-- RIGHT SIDE -->
        <div class="flex items-center gap-4">

            <!-- NOTIF ICON -->
            <div class="relative">
                <button id="notif-toggle" class="text-2xl hover:scale-110 transition">🔔</button>
                <span id="notif-badge" 
                      class="absolute -top-2 -right-2 bg-orange-600 text-white text-xs font-extrabold w-6 h-6 rounded-full flex justify-center items-center shadow">
                    0
                </span>

                <div id="notif-dropdown"
                     class="hidden absolute right-0 mt-3 bg-white text-gray-800 w-80 rounded-2xl shadow-xl border border-orange-200 p-3 space-y-2 max-h-96 overflow-y-auto">
                    <h3 class="text-sm font-extrabold text-orange-600 text-center mb-2 border-b pb-2">Order Masuk 🍜</h3>
                    <div id="notif-list" class="space-y-2"></div>
                </div>
            </div>

            <span class="text-sm text-gray-600">
                Login as: <b class="text-orange-600">{{ auth()->user()->name }}</b>
            </span>

            <form action="/logout" method="POST">
                @csrf
                <button class="px-4 py-2 bg-black text-white rounded-full text-sm font-extrabold shadow-lg hover:scale-105 transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<main class="max-w-7xl mx-auto px-4">
    {{ $slot ?? '' }} 
    @yield('content')
</main>

<!-- ✅ SCRIPT POLLING NOTIF -->
<script>
let lastUnread = 0;

async function fetchNotifs() {
  const badge = document.getElementById("notif-badge");
  const list  = document.getElementById("notif-list");
  const dropdown = document.getElementById("notif-dropdown");

  try {
    const res = await fetch("{{ route('api.notifications') }}"); // ✅ ini benar
    const data = await res.json();
    const unread = data.unread ?? 0;

    badge.innerText = unread;

    // 🔊 Play sound jika bertambah
    if (unread > lastUnread && unread > 0) {
      new Audio("{{ asset('storage/notif.mp3') }}").play();
    }
    lastUnread = unread;

    // Isi list
    if (!data.notifications || data.notifications.length === 0) {
      list.innerHTML = `<p class="text-center text-xs text-gray-500">Belum ada notifikasi</p>`;
      return;
    }

    list.innerHTML = data.notifications.map(n => `
      <div class="notif-item bg-orange-50 border-l-4 border-orange-600 p-3 rounded-xl shadow-sm cursor-pointer hover:scale-[1.01] transition"
           onclick="window.location='${"{{ route('transactions.invoice.show','__kode__') }}".replace('__kode__', n.data.invoice)}'">
        <div>
          <p class="text-sm font-extrabold text-orange-600">${n.data.invoice}</p>
          <p class="text-xs text-gray-600">${n.data.nama_pelanggan} • Meja ${n.data.nomor_meja}</p>
          <p class="text-xs font-bold text-gray-700">Total: Rp ${Number(n.data.total).toLocaleString('id-ID')}</p>
        </div>
      </div>
    `).join("");

    dropdown.classList.remove("hidden"); // tampilkan jika ada notif

  } catch (err) {
    console.error("Notif Error:", err);
  }
}

// toggle dropdown klik manual
document.getElementById("notif-toggle").addEventListener("click", () => {
  document.getElementById("notif-dropdown").classList.toggle("hidden");
});

document.addEventListener("DOMContentLoaded", () => {
  setInterval(fetchNotifs, 5000);
  fetchNotifs();
});
</script>

</body>
</html>

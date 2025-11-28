@php
use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
<title>Baso QR Menu</title>
</head>
<body class="bg-orange-50 flex items-center justify-center h-screen px-4">

<div class="bg-white rounded-3xl shadow-xl max-w-sm w-full p-6 text-center">
  <h1 class="text-2xl font-extrabold text-orange-600 mb-4">🍜 Baso QR Menu</h1>
  <div class="flex justify-center mb-4">
    {!! QrCode::size(200)->generate(route('customer.menu')) !!}
  </div>
  <p class="text-sm text-gray-600">Scan QR untuk melihat menu dan mulai memesan</p>
</div>

</body>
</html>


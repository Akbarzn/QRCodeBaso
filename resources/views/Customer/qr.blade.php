@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center h-[70vh] text-center">
    <h1 class="text-2xl font-bold mb-4">Scan untuk lihat menu</h1>

    <!-- contoh QR statis dulu -->
    <img src="/images/qrcode-test.png" class="w-48 h-48 mb-4">

    <a href="{{ route('customer.menu') }}" 
       class="px-4 py-2 bg-black text-white rounded-lg text-sm">
       Lihat Menu
    </a>
</div>
@endsection

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Import controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CostumerController;
use App\Models\Transaction;

// --------------------
// Public (Landing)
// --------------------
Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';
// Breeze dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --------------------
// CUSTOMER ROUTES (untuk pelanggan, NO AUTH)
// --------------------
Route::prefix('customer')->group(function () {
    // Halaman QR scan
    Route::get('/', [CostumerController::class, 'index'])->name('customer.qr');
    
    // List menu
    Route::get('/menu', [CostumerController::class, 'menuList'])->name('customer.menu');
    
    // Submit order
    Route::post('/order', [CostumerController::class, 'store'])->name('customer.order');
    

Route::post('/customer/checkout', [CostumerController::class, 'checkout'])->name('customer.checkout');
    // Success page
    Route::get('/success/{transaction}', [CostumerController::class, 'success'])->name('customer.success');
});


// --------------------
// WEBHOOK MIDTRANS (di luar auth ✅)
// --------------------
Route::post('/webhook/midtrans', function (Request $request) {
    $payload = $request->all();
    Log::info('MIDTRANS WEBHOOK RECEIVED', $payload);

    $orderId = $payload['order_id'] ?? null;
    $trx = Transaction::where('kode_transaksi', $orderId)->first();

    if (!$trx) {
        Log::error('TRANSACTION NOT FOUND', $payload);
        return response()->json(['message' => 'Transaction not found'], 404);
    }

    $status = $payload['transaction_status'] ?? '';

    if ($status === 'settlement') {
        $trx->update([
            'payment_status' => 'paid',
            'status' => 'selesai'
        ]);
    }

    if (in_array($status, ['deny','expire','cancel','failure','failure'])) {
        $trx->update([
            'payment_status' => 'failed',
            'status' => 'batal'
        ]);
    }

    return response()->json(['status' => 'ok'], 200);
})->name('midtrans.webhook');

// --------------------
// PANEL ROUTES (admin/kasir harus login ✅)
// --------------------
Route::prefix('panel')->middleware(['auth'])->group(function () {

    // CRUD admin
    Route::resource('categories', CategoryController::class);
    Route::resource('menus', MenuController::class)->except(['show']);
    Route::resource('transactions', TransactionController::class)->except(['edit']);

    // Restore
    Route::post('categories/{category}/restore', [CategoryController::class,'restore'])->name('categories.restore');
    Route::post('transactions/{transaction}/restore', [TransactionController::class,'restore'])->name('transactions.restore');

    // KONFIRMASI PEMBAYARAN MANUAL (FIX ✅, kepanggil, gak 403 lagi)
    Route::patch('/transactions/{transaction}/confirm-payment', [TransactionController::class, 'confirmPayment'])
        ->name('transactions.confirmPayment');

});


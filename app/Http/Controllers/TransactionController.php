<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\MenuTransaction;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function __construct()
    {
    }

    /** ✅ List Semua transaksi */
    public function index()
    {
        $transactions = Transaction::with('menuTransactions.menu')
            ->latest()
            ->get();

        return view('transactions.index', compact('transactions'));
    }

    /** ✅ Tampilkan form create transaction */
    public function create()
    {
        $menus = Menu::where('status', true)->get();
        return view('transactions.create', compact('menus'));
    }

    /** ✅ Simpan transaksi manual / buat payment link Snap */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:120',
            'nomor_meja' => 'required|string|max:20',
            'metode_pembayaran' => 'required|in:manual,midtrans',
            'menus' => 'required|array|min:1',
            'menus.*.menu_id' => 'required|exists:menus,id',
            'menus.*.jumlah' => 'required|integer|min:1',
            'menus.*.catatan' => 'nullable|string|max:255',
        ]);

        /** ✅ Buat transaksi */
        $transaction = Transaction::create([
            'kode_transaksi' => 'TRX-' . Str::upper(Str::random(6)),
            'nama_pelanggan' => $validated['nama_pelanggan'],
            'nomor_meja' => $validated['nomor_meja'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status' => 'menunggu',
            'payment_status' => 'pending',
            'total_harga' => 0,
        ]);

        /** ✅ Simpan pesanan menu ke pivot */
        foreach ($validated['menus'] as $item) {
            $menu = Menu::find($item['menu_id']);

            MenuTransaction::create([
                'transaction_id' => $transaction->id,
                'menu_id' => $menu->id,
                'jumlah' => $item['jumlah'],
                'harga' => $menu->harga,
                'subtotal' => $menu->harga * $item['jumlah'],
                'catatan' => $item['catatan'],
            ]);
        }

        /** ✅ Update total */
        $total = $transaction->menuTransactions()->sum('subtotal');
        $transaction->update(['total_harga' => $total]);

        /** ✅ Jika metode = midtrans → generate snap_url dulu */
        if ($validated['metode_pembayaran'] === 'midtrans') {
            \Midtrans\Config::$serverKey = config('midtrans.server_key') ?? env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $payload = [
                "transaction_details" => [
                    "order_id" => $transaction->kode_transaksi,
                    "gross_amount" => (int) $total,
                ],
                "customer_details" => [
                    "first_name" => $validated['nama_pelanggan'],
                    "email" => Auth::user()->email ?? "kasir@mail.com",
                    "phone" => "-",
                ],
            ];

            try {
                $snap = \Midtrans\Snap::createTransaction($payload);

                // 🔥 Simpan snap_url ke DB
                $transaction->update([
                    "snap_url" => $snap->redirect_url
                ]);

            } catch (\Exception $e) {
                Log::error("Midtrans Snap Error:", [$e->getMessage()]);
                return back()->with('error', "Gagal generate payment link Midtrans ❌");
            }

            // ✅ Langsung ke halaman midtrans-pay kamu sendiri
            return redirect()->route('transactions.midtransPay', $transaction->kode_transaksi);
        }

        /** ✅ Jika manual → langsung simpan order */
        return redirect()->route('transactions.index')->with('success', "Transaksi manual berhasil dibuat ✅");
    }

    /** ✅ Detail transaksi */
    public function show(Transaction $transaction)
    {
        return view('transactions.detail', compact('transaction'));
    }

    /** ✅ Hapus transaksi (soft delete) */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return back()->with('success', "Transaksi berhasil dihapus 🗑️");
    }

    /** ✅ Restore transaksi */
    public function restore($id)
    {
        $trx = Transaction::onlyTrashed()->findOrFail($id);
        $trx->restore();
        return back()->with('success','Transaksi direstore ♻️');
    }

    

    /** ✅ Konfirmasi pembayaran MANUAL oleh kasir/admin */
   public function confirmPayment(Transaction $transaction)
{
    $user = auth()->user();

    if (!$user->hasRole(['admin', 'kasir'])) {
        abort(403);
    }

    if ($transaction->metode_pembayaran !== 'manual' || $transaction->payment_status !== 'pending') {
        return back()->with('error', 'Tidak bisa dikonfirmasi');
    }

    $transaction->update([
        'payment_status' => 'paid',
        'status' => 'selesai',
    ]);

    return back()->with('success', 'Pembayaran manual dikonfirmasi ✅');
}


    /** ✅ Webhook Midtrans (diakses tanpa auth) */
    public function webhook(Request $request)
    {
        $payload = $request->all();
        Log::info("WEBHOOK MIDTRANS:", $payload);

        $code = $payload['order_id'] ?? null;

        $transaction = Transaction::where('kode_transaksi', $code)->first();

        if (!$transaction) {
            Log::error("WEBHOOK ORDER ID NOT FOUND", $payload);
            return response()->json(['message'=>'not found'], 404);
        }

        if (($payload['transaction_status'] ?? '') === 'settlement') {
            $transaction->update([
                'payment_status' => 'paid',
                'status' => 'selesai'
            ]);

            // kamu bisa tambah pengurangan stok di sini nanti
        }

        return response()->json(['message'=>'ok'], 200);
    }

    public function success(Transaction $transaction)
{
    return view('transactions.success', [
        'transaction' => $transaction
    ]);
}

public function showByInvoice($kode)
{
    $transaction = Transaction::where('kode_transaksi', $kode)->first();

    if (!$transaction) {
        abort(404, 'Invoice tidak ditemukan ❌');
    }

    
    $transactions = Transaction::where('status', '  ')
        ->orderByDesc('created_at')->get();
        
        return view('transactions.index', [
        'transactions'   => $transactions, // ✅ dipakai di index
        'transactionNow' => $transaction   // ✅ khusus item yang barusan diklik (detail)
    ]);
    // return view('transactions.index', compact('transaction'));
}


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\MenuTransaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class CostumerController extends Controller
{
    /** Landing QR */
    public function index()
    {
        return view('customer.index');
    }

    /** List menu */
    public function menuList()
    {
        $menus = Menu::where('status', true)->get();
        return view('customer.menu', compact('menus'));
    }

    /** Checkout & hitung subtotal per item */
    public function checkout(Request $request)
    {
        $menus = $request->menus ?? [];

        $filtered = [];
        $total = 0;

        foreach ($menus as $item) {
            $jumlah = (int)($item['jumlah'] ?? 0);
            if ($jumlah > 0) {
                $menu = Menu::find($item['menu_id']);
                if ($menu) {
                    $subtotal = $menu->harga * $jumlah;
                    $filtered[] = [
                        'menu_id'   => $menu->id,
                        'nama_menu'=> $menu->nama_menu,
                        'harga'     => $menu->harga,
                        'image'     => $menu->image,
                        'jumlah'    => $jumlah,
                        'catatan'   => $item['catatan'] ?? null,
                        'subtotal'  => $subtotal
                    ];
                    $total += $subtotal;
                }
            }
        }

        if (empty($filtered)) {
            return back()->with('error','Kamu belum pilih menu ❌');
        }

        session(['menus' => $filtered, 'total_harga' => $total]);

        $kode = 'TRX-' . Str::upper(Str::random(8)); // invoice unik baru tiap checkout

        return view('customer.checkout', [
            'kode_transaksi' => $kode,
            'nomor_meja' => '', // awalnya kosong, user bisa input
            'total' => $total
        ]);
    }

    /** Simpan order */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan'    => 'required|string|max:120',
            'nomor_meja'        => 'required|string|max:20',
            'metode_pembayaran' => 'required|in:manual,midtrans',
            'menus'             => 'array'
        ]);

        $menus = session('menus', []);
        $total = session('total_harga', 0);

        $transaction = Transaction::create([
            'kode_transaksi'    => $request->kode_transaksi,
            'nama_pelanggan'   => $validated['nama_pelanggan'],
            'nomor_meja'       => $validated['nomor_meja'],
            'metode_pembayaran'=> $validated['metode_pembayaran'],
            'status'           => 'menunggu',
            'payment_status'   => 'unpaid',
            'total_harga'      => $total,
            'snap_url'         => null,
        ]);

        foreach ($menus as $item) {
            MenuTransaction::create([
                'transaction_id'=> $transaction->id,
                'menu_id'  => $item['menu_id'],
                'jumlah'   => $item['jumlah'],
                'harga'    => $item['harga'],
                'subtotal' => $item['subtotal'],
                'catatan'  => $item['catatan']
            ]);
        }

        session()->forget(['menus','total_harga']);

        // redirect ke halaman success
        return redirect()->route('customer.success', $transaction->id)
            ->with('success','Order berhasil ✅');
    }

    /** Success Page (lebih detail) */
    public function success(Transaction $transaction)
    {
        return view('customer.success', compact('transaction'));
    }
}

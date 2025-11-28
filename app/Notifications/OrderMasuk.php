<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Transaction;

class OrderMasuk extends Notification
{
    use Queueable;

    public function __construct(public Transaction $transaction) {}

    public function via($notifiable)
    {
        return ['database']; // ✅ simpan ke tabel notifications Laravel bawaan
    }

    public function toArray($notifiable)
    {
        return [
            'invoice'        => $this->transaction->kode_transaksi,
            'nama_pelanggan' => $this->transaction->nama_pelanggan,
            'nomor_meja'    => $this->transaction->nomor_meja,
            'total'         => $this->transaction->total_harga,
            'menus' => $this->transaction->menuTransactions->map(fn($m) => [
                'nama_menu' => $m->menu->nama_menu,
                'jumlah'    => $m->jumlah,
                'harga'     => $m->harga,
                'subtotal'  => $m->subtotal
            ])->toArray()
        ];
    }
}

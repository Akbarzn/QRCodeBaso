<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderDatabaseNotification extends Notification
{
    use Queueable;

    protected $transaction;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    /** Simpan ke database */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'invoice'   => $this->transaction->kode_transaksi,
            'meja'      => $this->transaction->nomor_meja,
            'nama'      => $this->transaction->nama_pelanggan,
            'total'     => $this->transaction->total_harga,
            'menus'     => $this->transaction->menuTransactions->map(function($item) {
                return [
                    'nama_menu' => $item->menu->nama_menu,
                    'jumlah'    => $item->jumlah,
                    'subtotal'  => $item->subtotal,
                ];
            }),
        ];
    }
}

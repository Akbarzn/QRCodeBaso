<?php

namespace App\Listeners;

use App\Events\OrderBaru;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KirimNotifikasiOrderBaru
{
    public function handle(OrderBaru $event): void
    {
        $trx = $event->transaction;

        // Cari semua user admin
        $admins = User::role('admin')->get(); // kalau pakai spatie
        // Kalau belum pakai Spatie, pakai where biasa:
        // $admins = User::where('email','admin@mail.com')->get();

        foreach ($admins as $admin) {
            DB::table('notifications')->insert([
                'id' => \Str::uuid(),
                'type' => 'order_masuk',
                'notifiable_type' => get_class($admin),
                'notifiable_id' => $admin->id,
                'data' => json_encode([
                    'invoice' => $trx->kode_transaksi,
                    'nama_pelanggan' => $trx->nama_pelanggan,
                    'nomor_meja' => $trx->nomor_meja,
                    'total' => $trx->total_harga,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Log::info("Notifikasi order masuk berhasil disimpan ✅ untuk ".count($admins)." admin");
    }
}

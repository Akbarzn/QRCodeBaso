<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction; // ✅ INI YANG BENAR, bukan App\Events\Transaction

class OrderBaru
{
    use Dispatchable, SerializesModels;

    public $transaction;

    public function __construct(Transaction $transaction) // ✅ Type hint pakai Model Transaction
    {
        $this->transaction = $transaction;
    }
}

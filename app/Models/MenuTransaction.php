<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MenuTransaction extends Model {
    protected $guarded = [];

    public function menu() {
        return $this->belongsTo(Menu::class, 'menu_id')->withTrashed();
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id')->withTrashed();
    }
}


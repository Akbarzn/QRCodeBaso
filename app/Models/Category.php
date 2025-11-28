<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model {
    use SoftDeletes;
protected $table = 'categories';
    protected $guarded = []; // aman, karena kamu kontrol input di controller

    public function menus() {
        return $this->hasMany(Menu::class);
    }
}

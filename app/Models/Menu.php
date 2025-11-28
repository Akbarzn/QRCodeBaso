<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;
    protected $table = 'menus';
    protected $guarded = []; // kamu mau guarded ✅

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }
}


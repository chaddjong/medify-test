<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['kode', 'nama'];

    // Relasi many-to-many ke MasterItem
    public function items()
    {
        return $this->belongsToMany(
            MasterItem::class,       // Model lawan
            'category_master_item',  // Nama tabel pivot sesuai DB
            'category_id',           // Foreign key di tabel pivot untuk Category
            'master_item_id'         // Foreign key di tabel pivot untuk MasterItem
        );
    }
}

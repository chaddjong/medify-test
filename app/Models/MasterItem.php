<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterItem extends Model
{
   public function categories()
   {
     return $this->belongsToMany(Category::class, 'category_master_item', 'master_item_id', 'category_id');
   }
}

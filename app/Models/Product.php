<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku_code', 'name', 'description'
    ];

    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    public function finishedGoodsInventories()
    {
        return $this->hasMany(FinishedGoodsInventory::class);
    }
}

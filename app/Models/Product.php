<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'sku_code', 'description', 'unit', 'category'
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
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'sku_code', 'description', 'unit', 'jeniss', 'image'
    ];

    protected static function booted()
    {
        static::created(function ($product) {
            if (empty($product->sku_code)) {
                $product->sku_code = 'PRD-' . str_pad($product->id, 6, '0', STR_PAD_LEFT) . '-01';
                $product->save();
            }
        });
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    public function finishedGoodsInventories()
    {
        return $this->hasMany(FinishedGoodsInventory::class);
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
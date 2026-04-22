<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $fillable = [
        'name', 'sku', 'type', 'unit', 'current_stock', 'min_stock_level', 'supplier'
    ];

    protected $casts = [
        'current_stock' => 'integer',
        'min_stock_level' => 'integer',
    ];

    public function productionMaterials()
    {
        return $this->hasMany(ProductionMaterial::class);
    }

    public function getStockStatusAttribute()
    {
        $minLevel = $this->min_stock_level ?? 10;
        if ($this->current_stock <= 0) return 'out';
        if ($this->current_stock < $minLevel) return 'low';
        return 'available';
    }
}
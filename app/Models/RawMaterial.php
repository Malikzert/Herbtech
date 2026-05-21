<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $fillable = [
        'name', 'sku', 'type', 'unit', 'current_stock', 'min_stock_level', 'supplier', 'image', 'qc_status'
    ];

    protected $casts = [
        'current_stock' => 'integer',
        'min_stock_level' => 'integer',
    ];

    protected static function booted()
    {
        static::created(function ($rawMaterial) {
            if (empty($rawMaterial->sku)) {
                $rawMaterial->sku = 'RM-' . str_pad($rawMaterial->id, 6, '0', STR_PAD_LEFT) . '-01';
                $rawMaterial->save();
            }
        });
    }

    public function qcRecords()
    {
        return $this->hasMany(RawMaterialQc::class);
    }

    public function latestQc()
    {
        return $this->hasOne(RawMaterialQc::class)->latestOfMany();
    }

    public function productionMaterials()
    {
        return $this->hasMany(ProductionMaterial::class);
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function getStockStatusAttribute()
    {
        $minLevel = $this->min_stock_level ?? 10;
        if ($this->current_stock <= 0) return 'out';
        if ($this->current_stock < $minLevel) return 'low';
        return 'available';
    }
}
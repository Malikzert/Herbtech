<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $fillable = [
        'batch_number', 'product_id', 'start_date', 'end_date', 'status', 'pic_name', 'user_id', 'rework_of'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productionMaterials()
    {
        return $this->hasMany(ProductionMaterial::class);
    }

    public function qualityControls()
    {
        return $this->hasMany(QualityControl::class);
    }

    public function finishedGoodsInventories()
    {
        return $this->hasMany(FinishedGoodsInventory::class);
    }

    public function reworkProduction()
    {
        return $this->hasMany(Production::class, 'rework_of');
    }

    public function originalProduction()
    {
        return $this->belongsTo(Production::class, 'rework_of');
    }
}
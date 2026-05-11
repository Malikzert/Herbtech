<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $fillable = [
        'batch_number', 'product_id', 'target_quantity', 'actual_quantity', 'start_date', 'end_date', 'status', 'pic_name', 'user_id', 'rework_of',
        'priority_level', 'estimated_duration', 'algorithm_generated', 'scheduled_start', 'scheduled_end', 'schedule_notes', 'fitness_data'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'scheduled_start' => 'datetime',
        'scheduled_end' => 'datetime',
        'algorithm_generated' => 'boolean',
        'fitness_data' => 'array',
    ];

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeScheduled($query)
    {
        return $query->whereNotNull('scheduled_start')->where('algorithm_generated', true);
    }

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
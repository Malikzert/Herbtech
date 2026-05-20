<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scheduling extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'batch_number_recommendation',
        'recommended_quantity',
        'priority_order',
        'is_recommended',
        'critical_material_name',
        'rejection_reason',
        'status',
        'recom_date',
    ];

    protected $casts = [
        'is_recommended' => 'boolean',
        'recommended_quantity' => 'integer',
        'priority_order' => 'integer',
        'recom_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

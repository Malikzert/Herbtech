<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinishedGoodsInventory extends Model
{
    protected $fillable = [
        'production_id', 'product_id', 'quantity_added', 'expired_date', 'storage_location'
    ];

    protected $casts = [
        'expired_date' => 'date',
    ];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

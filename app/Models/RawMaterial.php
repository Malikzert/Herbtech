<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $fillable = [
        'name', 'type', 'unit', 'current_stock'
    ];

    public function productionMaterials()
    {
        return $this->hasMany(ProductionMaterial::class);
    }
}

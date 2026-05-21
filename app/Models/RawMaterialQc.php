<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterialQc extends Model
{
    protected $table = 'raw_material_qcs';

    protected $fillable = [
        'raw_material_id',
        'user_id',
        'total_qty_checked',
        'good_qty',
        'bad_qty',
        'qc_percentage',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_qty_checked' => 'integer',
        'good_qty' => 'integer',
        'bad_qty' => 'integer',
        'qc_percentage' => 'decimal:2',
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

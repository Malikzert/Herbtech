<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QcDefect extends Model
{
    protected $fillable = [
        'qc_id', 'defect_cat_id', 'defect_quantity', 'notes'
    ];

    public function qualityControl()
    {
        return $this->belongsTo(QualityControl::class, 'qc_id');
    }

    public function defectCategory()
    {
        return $this->belongsTo(DefectCategory::class, 'defect_cat_id');
    }
}

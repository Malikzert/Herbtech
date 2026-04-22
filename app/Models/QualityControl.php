<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityControl extends Model
{
    protected $fillable = [
        'production_id', 'inspector_name', 'inspected_at', 
        'total_inspected', 'total_passed', 'total_rejected', 'status', 'action'
    ];

    protected $casts = [
        'inspected_at' => 'datetime',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function qcDefects()
    {
        return $this->hasMany(QcDefect::class, 'qc_id');
    }
}
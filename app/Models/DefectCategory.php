<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefectCategory extends Model
{
    protected $fillable = [
        'name', 'severity'
    ];

    public function qcDefects()
    {
        return $this->hasMany(QcDefect::class, 'defect_cat_id');
    }
}

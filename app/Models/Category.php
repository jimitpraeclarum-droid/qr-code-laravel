<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'category_id';
    protected $fillable = [
        'category_key',
        'category_name',
        'icon',
        'active',
        'created_by',
        'updated_by'
    ];

    // One Category has many QRData
    public function qrData()
    {
        return $this->hasMany(QRData::class, 'category_id', 'category_id');
    }

    public function getRouteKeyName()
    {
        return 'category_id';
    }
}

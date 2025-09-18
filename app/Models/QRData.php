<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QRData extends Model
{
    protected $table = 'qr_data';
    protected $primaryKey = 'qrcode_id';
    protected $fillable = [
        'user_id',
        'title',
        'status',
        'category_id',
        'content',
        'created_by',
        'updated_by',
        'is_protected',
        'end_date',
        'qrcode_key',
        'qrcode_password',
        'qrcode_image',
        // Design fields
        'bg_color',
        'square_color',
        'pixel_color',
        'pattern_style',
        'frame_style',
        'logo_path',
        'logo_size'
    ];

    // QRData belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // QRData belongs to a Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    // QRData has many QRHistory records
    public function history()
    {
        return $this->hasMany(QRHistory::class, 'qrcode_id', 'qrcode_id');
    }
}

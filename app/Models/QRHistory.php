<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QRHistory extends Model
{
    protected $table = 'qr_history';
    protected $primaryKey = 'qrcodecount_id';
    protected $fillable = [
        'qrcode_id',
        'city',
        'state',
        'country',
        'ip_address',
        'browser',
        'device'
    ];

    // QRHistory belongs to a QRData
    public function qrData()
    {
        return $this->belongsTo(QRData::class, 'qrcode_id', 'qrcode_id');
    }
}

<?php
// app/Models/ShodanHost.php

namespace App\Models;

use App\Models\Port;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShodanHost extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip', 'hostnames', 'country', 'city', 'ports', 'vulns',
        'isp', 'domains', 'organization', 'asn', 'service_banners','email', // Tambahkan di sini
    ];

    protected $casts = [
        'ports' => 'array',
        'vulns' => 'array',
        'domains' => 'array',
        'organization' => 'array',
        'hostnames' => 'array',
        'service_banners' => 'array', // Tambahkan casting ke array
    ];

    public function ports()
    {
        return $this->hasMany(Port::class);
    }
}

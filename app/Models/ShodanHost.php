<?php
// app/Models/ShodanHost.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShodanHost extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip', 'hostnames', 'country', 'city', 'ports', 'vulns',
        'isp', 'domains', 'organization', 'asn', 'service_banners', // Tambahkan di sini
    ];

    protected $casts = [
        'ports' => 'array',
        'vulns' => 'array',
        'domains' => 'array',
        'organization' => 'array',
        'hostnames' => 'array',
        'service_banners' => 'array', // Tambahkan casting ke array
    ];
}

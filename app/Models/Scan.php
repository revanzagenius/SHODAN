<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip',
        'os',
        'isp',
        'org',
        'ports',
        'vulns',
    ];
}

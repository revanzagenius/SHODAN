<?php

namespace App\Models;

use App\Models\ShodanHost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Port extends Model
{
    use HasFactory;

    protected $fillable = [
        'shodan_host_id', 'port_number', 'asset_group', 'trigger', 'version', 'details'
    ];

    public function shodanHost()
    {
        return $this->belongsTo(ShodanHost::class);
    }
}

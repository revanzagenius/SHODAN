<?php

namespace App\Http\Controllers;

use App\Models\Ip;
use Illuminate\Http\Request;
use App\Services\ShodanService;

class DashboardController extends Controller
{
    protected $shodanService;

    public function __construct(ShodanService $shodanService)
    {
        $this->shodanService = $shodanService;
    }

    public function index(Request $request)
    {
        // Default IP untuk demo, bisa diubah berdasarkan input user
        $ip = $request->input('ip', '8.8.8.8'); // Contoh IP default: Google DNS
        $hostInfo = $this->shodanService->getHostInformation($ip);

        return view('index', ['hostInfo' => $hostInfo, 'ip' => $ip]);
    }

    public function addMonitor(Request $request)
    {
        // Validasi input
        $request->validate([
            'ip' => 'required|ip',
        ]);

        // Menambahkan IP ke monitor
        $result = $this->shodanService->addMonitor($request->ip);

        return redirect()->back()->with('status', $result);
    }
}

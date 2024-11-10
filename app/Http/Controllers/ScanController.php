<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function storeScan(Request $request)
    {
        // Mendapatkan data hasil pemindaian
        $scanData = [
            'ip' => $data['ip'],
            'os' => $data['os'],
            'isp' => $data['isp'],
            'org' => $data['org'],
            'ports' => $data['ports'],
            'vulns' => json_encode($data['cve_details']), // Menyimpan data CVE sebagai JSON
        ];

        // Menyimpan ke database
        Scan::create($scanData);

        // Redirect atau return view sesuai kebutuhan
        return redirect()->route('scan.results');
    }
}

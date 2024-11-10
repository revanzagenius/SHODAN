<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException; // Tambahkan ini untuk penanganan kesalahan

class ShodanController extends Controller
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('SHODAN_API_KEY'); // Simpan API Key di .env
    }

    public function index()
    {
        return view('shodan'); // Buat view shodan.blade.php untuk antarmuka pengguna
    }

    public function scan(Request $request)
    {
        $ip = $request->input('ip');
        $data = [];
    
        try {
            // Mengambil informasi host dari Shodan
            $response = $this->client->get("https://api.shodan.io/shodan/host/$ip?key={$this->apiKey}");
            $result = json_decode($response->getBody(), true);
    
            // Ambil data yang diperlukan dari hasil
            $data = [
                'ip' => $result['ip_str'] ?? 'N/A',
                'os' => $result['os'] ?? 'N/A',
                'isp' => $result['isp'] ?? 'N/A',
                'org' => $result['org'] ?? 'N/A',
                'ports' => implode(', ', $result['ports'] ?? []), // Pastikan ports ada
                'vulns' => $result['vulns'] ?? [], // Pastikan vulns ada
            ];
        } catch (RequestException $e) {
            // Tangani kesalahan jika permintaan ke Shodan gagal
            return redirect()->back()->withErrors(['error' => 'Gagal mengambil data dari Shodan: ' . $e->getMessage()]);
        }
    
        // Mendapatkan deskripsi CVE
        $data['cve_details'] = [];
        foreach ($data['vulns'] as $cve) {
            try {
                // Mengambil deskripsi CVE dari API CVE Details
                $cveResponse = $this->client->get("https://cve.circl.lu/api/cve/$cve");
                $cveData = json_decode($cveResponse->getBody(), true);
                // Simpan CVE dan deskripsi dalam format yang diinginkan
                $data['cve_details'][$cve] = $cveData['summary'] ?? 'No description available';
            } catch (RequestException $e) {
                // Tangani kesalahan jika permintaan ke CVE gagal
                $data['cve_details'][$cve] = 'Error retrieving CVE details: ' . $e->getMessage();
            }
        }
    
        return view('results', compact('data')); // Tampilkan hasil di view results.blade.php
    }
    
}

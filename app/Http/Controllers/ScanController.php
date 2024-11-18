<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Client\RequestException;

class ScanController extends Controller
{
    protected $client;
    protected $shodanApiKey;
    protected $virusTotalApiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->shodanApiKey = env('SHODAN_API_KEY'); // Simpan API Key di .env
        $this->virusTotalApiKey = env('VIRUS_TOTAL_API_KEY'); // Simpan API Key VirusTotal di .env
        $this->otxApiKey = env('OTX_API_KEY');
    }

    public function index()
    {
        return view('scanner'); // Buat view shodan.blade.php untuk antarmuka pengguna
    }

    public function scan(Request $request)
    {
        $ip = $request->input('ip');
        $data = [];

        try {
            // Mengambil informasi host dari Shodan
            $response = $this->client->get("https://api.shodan.io/shodan/host/$ip?key={$this->shodanApiKey}");
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

        // Mendapatkan deskripsi CVE dari Shodan
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

        // Mengambil hasil pemindaian dari VirusTotal
        try {
            $virusTotalResponse = $this->client->get("https://www.virustotal.com/api/v3/ip_addresses/$ip", [
                'headers' => [
                    'x-apikey' => $this->virusTotalApiKey,
                ],
            ]);
            $virusTotalResult = json_decode($virusTotalResponse->getBody(), true);
            $data['virus_total'] = $virusTotalResult['data'] ?? null; // Menyimpan hasil VirusTotal
        } catch (RequestException $e) {
            // Tangani kesalahan jika permintaan ke VirusTotal gagal
            $data['virus_total'] = 'Gagal mengambil data dari VirusTotal: ' . $e->getMessage();
        }

        return view('results', compact('data')); // Tampilkan hasil di view results.blade.php

        // Mengambil hasil pemindaian dari OTX
    // Mengambil hasil pemindaian dari OTX
    try {
        $otxResponse = $this->client->get("https://otx.alienvault.com/api/v1/indicators/IPv4/{$ip}", [
            'headers' => [
                'X-OTX-API-KEY' => $this->otxApiKey,
            ],
        ]);
        $otxResult = json_decode($otxResponse->getBody(), true);
        $data['otx'] = $otxResult ?? 'Gagal mengambil data dari OTX.';
    } catch (RequestException $e) {
        $data['otx'] = 'Gagal mengambil data dari OTX: ' . $e->getMessage();
    }

    // Menampilkan hasil scan pada view
    return view('results', compact('data'));

    }

    public function search(Request $request)
    {
        $apiKey = 'YOUR_SHODAN_API_KEY';  // Ganti dengan API key Shodan Anda
        $query = $request->input('query'); // Parameter pencarian dari pengguna

        $client = new Client();
        
        try {
            $response = $client->request('GET', 'https://api.shodan.io/shodan/host/search', [
                'query' => [
                    'key' => $apiKey,
                    'query' => $query,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            // Mengirim data ke view
            return view('shodan_search', ['data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal melakukan pencarian ke Shodan: ' . $e->getMessage()], 500);
        }
    }
}
